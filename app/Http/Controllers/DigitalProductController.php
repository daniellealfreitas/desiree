<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DigitalProductController extends Controller
{
    /**
     * Exibe a lista de downloads disponíveis para o usuário.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar todos os itens de pedidos do usuário que são produtos digitais
        $orderItems = OrderItem::whereHas('order', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereIn('status', [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED, Order::STATUS_DELIVERED]);
        })->whereHas('product', function ($query) {
            $query->where('is_digital', true);
        })->with(['product', 'order'])->get();
        
        // Buscar informações de download para cada item
        $downloads = [];
        foreach ($orderItems as $item) {
            $download = ProductDownload::firstOrCreate(
                [
                    'order_item_id' => $item->id,
                    'user_id' => $user->id,
                    'product_id' => $item->product_id,
                ],
                [
                    'download_count' => 0,
                    'expires_at' => $item->product->download_expiry_days 
                        ? now()->addDays($item->product->download_expiry_days) 
                        : null,
                ]
            );
            
            $downloads[] = [
                'item' => $item,
                'download' => $download,
                'is_valid' => $download->isValid(),
            ];
        }
        
        return view('shop.downloads', [
            'downloads' => $downloads,
        ]);
    }
    
    /**
     * Processa o download de um produto digital.
     */
    public function download($id)
    {
        $user = Auth::user();
        
        // Buscar o registro de download
        $download = ProductDownload::where('id', $id)
            ->where('user_id', $user->id)
            ->with(['product', 'orderItem.order'])
            ->firstOrFail();
        
        // Verificar se o download ainda é válido
        if (!$download->isValid()) {
            return back()->with('error', 'Este download não está mais disponível ou expirou.');
        }
        
        // Verificar se o pedido está em um status válido
        $validStatuses = [Order::STATUS_PROCESSING, Order::STATUS_COMPLETED, Order::STATUS_DELIVERED];
        if (!in_array($download->orderItem->order->status, $validStatuses)) {
            return back()->with('error', 'O pedido relacionado a este download não está em um status válido.');
        }
        
        // Verificar se o arquivo existe
        $filePath = str_replace('/storage', 'public', $download->product->digital_file);
        if (!Storage::exists($filePath)) {
            return back()->with('error', 'O arquivo não foi encontrado.');
        }
        
        // Incrementar o contador de downloads
        $download->incrementDownloadCount();
        
        // Preparar o nome do arquivo para download
        $fileName = $download->product->digital_file_name ?: basename($download->product->digital_file);
        
        // Retornar o arquivo para download
        return Storage::download($filePath, $fileName);
    }
}
