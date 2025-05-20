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
        Schema::table('products', function (Blueprint $table) {
            // Verificar e adicionar colunas que estão faltando
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('price');
            }
            
            if (!Schema::hasColumn('products', 'sale_starts_at')) {
                $table->timestamp('sale_starts_at')->nullable()->after('sale_price');
            }
            
            if (!Schema::hasColumn('products', 'sale_ends_at')) {
                $table->timestamp('sale_ends_at')->nullable()->after('sale_starts_at');
            }
            
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('products', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('slug')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('products', 'featured')) {
                $table->boolean('featured')->default(false)->after('stock');
            }
            
            if (!Schema::hasColumn('products', 'status')) {
                $table->string('status')->default('active')->after('featured');
            }
            
            if (!Schema::hasColumn('products', 'dimensions')) {
                $table->json('dimensions')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('products', 'options')) {
                $table->json('options')->nullable()->after('dimensions');
            }
            
            if (!Schema::hasColumn('products', 'is_digital')) {
                $table->boolean('is_digital')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('products', 'digital_file')) {
                $table->string('digital_file')->nullable()->after('is_digital');
            }
            
            if (!Schema::hasColumn('products', 'digital_file_name')) {
                $table->string('digital_file_name')->nullable()->after('digital_file');
            }
            
            if (!Schema::hasColumn('products', 'download_limit')) {
                $table->integer('download_limit')->nullable()->after('digital_file_name');
            }
            
            if (!Schema::hasColumn('products', 'download_expiry_days')) {
                $table->integer('download_expiry_days')->nullable()->after('download_limit');
            }
            
            // Renomear is_active para status se necessário
            if (Schema::hasColumn('products', 'is_active') && Schema::hasColumn('products', 'status')) {
                // Não podemos renomear diretamente no SQLite, então vamos atualizar os valores
                DB::statement('UPDATE products SET status = CASE WHEN is_active = 1 THEN "active" ELSE "inactive" END');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não vamos remover as colunas no down para evitar perda de dados
    }
};
