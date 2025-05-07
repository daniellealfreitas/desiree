<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'category_id')) {
                    $table->foreignId('category_id')->nullable()->after('slug')->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('products', 'featured')) {
                    $table->boolean('featured')->default(false)->after('stock');
                }
                if (!Schema::hasColumn('products', 'status')) {
                    $table->string('status')->default('active')->after('featured');
                }
                if (!Schema::hasColumn('products', 'sku')) {
                    $table->string('sku')->nullable()->unique()->after('status');
                }
                if (!Schema::hasColumn('products', 'weight')) {
                    $table->decimal('weight', 8, 2)->nullable()->after('sku');
                }
                if (!Schema::hasColumn('products', 'dimensions')) {
                    $table->json('dimensions')->nullable()->after('weight');
                }
                if (!Schema::hasColumn('products', 'options')) {
                    $table->json('options')->nullable()->after('dimensions');
                }
                if (!Schema::hasColumn('products', 'sale_price')) {
                    $table->decimal('sale_price', 10, 2)->nullable()->after('price');
                }
                if (!Schema::hasColumn('products', 'sale_starts_at')) {
                    $table->timestamp('sale_starts_at')->nullable()->after('sale_price');
                }
                if (!Schema::hasColumn('products', 'sale_ends_at')) {
                    $table->timestamp('sale_ends_at')->nullable()->after('sale_starts_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $columns = [
                    'category_id', 'featured', 'status', 'sku', 'weight', 'dimensions',
                    'options', 'sale_price', 'sale_starts_at', 'sale_ends_at'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
