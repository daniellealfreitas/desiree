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
            });
        }

        // Adicionar campo para rastrear downloads de produtos digitais
        if (!Schema::hasTable('product_downloads')) {
            Schema::create('product_downloads', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('download_count')->default(0);
                $table->timestamp('last_download')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->timestamps();
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
                    'is_digital', 'digital_file', 'digital_file_name',
                    'download_limit', 'download_expiry_days'
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('products', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('product_downloads');
    }
};
