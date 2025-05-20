<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Log;

try {
    Log::info('Starting product creation test');
    
    $product = new Product();
    $product->name = 'Test Product Script';
    $product->slug = 'test-product-script';
    $product->price = 100;
    $product->sale_price = 90;
    $product->stock = 10;
    $product->status = 'active';
    $product->featured = false;
    $product->is_digital = false;
    
    $saved = $product->save();
    
    Log::info('Product saved: ' . ($saved ? 'Yes' : 'No'), [
        'product_id' => $product->id,
        'product_name' => $product->name
    ]);
    
    echo "Product created successfully with ID: " . $product->id . "\n";
} catch (\Exception $e) {
    Log::error('Error creating product: ' . $e->getMessage(), [
        'exception' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    echo "Error: " . $e->getMessage() . "\n";
}
