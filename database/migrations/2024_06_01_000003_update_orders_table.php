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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'discount')) {
                    $table->decimal('discount', 10, 2)->default(0)->after('total');
                }
                if (!Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method')->nullable()->after('status');
                }
                if (!Schema::hasColumn('orders', 'payment_id')) {
                    $table->string('payment_id')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('orders', 'shipping_address')) {
                    $table->json('shipping_address')->nullable()->after('payment_id');
                }
                if (!Schema::hasColumn('orders', 'shipping_cost')) {
                    $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_address');
                }
                if (!Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('shipping_cost');
                }
                if (!Schema::hasColumn('orders', 'coupon_id')) {
                    $table->foreignId('coupon_id')->nullable()->after('notes')->constrained()->onDelete('set null');
                }
                if (!Schema::hasColumn('orders', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->after('coupon_id');
                }
            });
        } else {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('total', 10, 2);
                $table->decimal('discount', 10, 2)->default(0);
                $table->string('status')->default('pending');
                $table->string('payment_method')->nullable();
                $table->string('payment_id')->nullable();
                $table->json('shipping_address')->nullable();
                $table->decimal('shipping_cost', 10, 2)->default(0);
                $table->text('notes')->nullable();
                $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
                $table->string('tracking_number')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não vamos remover a tabela orders se ela já existia
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $columns = [
                    'discount', 'payment_method', 'payment_id', 'shipping_address',
                    'shipping_cost', 'notes', 'coupon_id', 'tracking_number'
                ];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('orders', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
