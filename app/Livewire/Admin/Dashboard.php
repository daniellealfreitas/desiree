<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalSales;
    public $totalOrders;
    public $totalProducts;
    public $totalUsers;
    public $recentOrders;
    public $lowStockProducts;
    public $salesData;
    public $topSellingProducts;

    public function mount()
    {
        // Total de vendas
        $this->totalSales = Order::whereIn('status', [
            Order::STATUS_COMPLETED,
            Order::STATUS_SHIPPED,
            Order::STATUS_DELIVERED
        ])->sum('total');

        // Total de pedidos
        $this->totalOrders = Order::count();

        // Total de produtos
        $this->totalProducts = Product::count();

        // Total de usuários
        $this->totalUsers = User::count();

        // Pedidos recentes
        $this->recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Produtos com estoque baixo
        $this->lowStockProducts = Product::where('stock', '<', 10)
            ->where('status', 'active')
            ->orderBy('stock', 'asc')
            ->limit(5)
            ->get();

        // Dados de vendas dos últimos 7 dias
        $this->salesData = $this->getSalesData();

        // Produtos mais vendidos
        $this->topSellingProducts = $this->getTopSellingProducts();
    }

    protected function getSalesData()
    {
        $days = 7;
        $salesData = [];

        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');

            $sales = Order::whereDate('created_at', $date)
                ->whereIn('status', [
                    Order::STATUS_COMPLETED,
                    Order::STATUS_SHIPPED,
                    Order::STATUS_DELIVERED
                ])
                ->sum('total');

            $salesData[] = [
                'date' => now()->subDays($i)->format('d/m'),
                'sales' => $sales
            ];
        }

        return array_reverse($salesData);
    }

    protected function getTopSellingProducts()
    {
        // Get the IDs of top selling products
        $topProductIds = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', [
                Order::STATUS_COMPLETED,
                Order::STATUS_SHIPPED,
                Order::STATUS_DELIVERED
            ])
            ->select(
                'products.id',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('products.id')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Get the product models with eager loaded images
        $productIds = $topProductIds->pluck('id')->toArray();
        $products = Product::with('images')->whereIn('id', $productIds)->get();

        // Merge the sales data with the product models
        return $topProductIds->map(function($item) use ($products) {
            $product = $products->firstWhere('id', $item->id);
            $product->total_quantity = $item->total_quantity;
            $product->total_sales = $item->total_sales;
            return $product;
        });
    }

    public function getStatusClass($status)
    {
        return match ($status) {
            Order::STATUS_PENDING => 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
            Order::STATUS_PROCESSING => 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200',
            Order::STATUS_COMPLETED => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            Order::STATUS_SHIPPED => 'bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200',
            Order::STATUS_DELIVERED => 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
            Order::STATUS_CANCELLED => 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200',
            Order::STATUS_REFUNDED => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
            'white' => 'bg-white dark:bg-zinc-800 text-gray-800 dark:text-gray-200',
            default => 'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200',
        };
    }

    public function getStatusName($status)
    {
        return match ($status) {
            Order::STATUS_PENDING => 'Pendente',
            Order::STATUS_PROCESSING => 'Processando',
            Order::STATUS_COMPLETED => 'Concluído',
            Order::STATUS_SHIPPED => 'Enviado',
            Order::STATUS_DELIVERED => 'Entregue',
            Order::STATUS_CANCELLED => 'Cancelado',
            Order::STATUS_REFUNDED => 'Reembolsado',
            default => 'Desconhecido',
        };
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.admin', ['title' => 'Dashboard']);
    }
}

