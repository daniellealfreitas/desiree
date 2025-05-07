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
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                if (!Schema::hasColumn('coupons', 'description')) {
                    $table->string('description')->nullable()->after('value');
                }
                if (!Schema::hasColumn('coupons', 'min_purchase')) {
                    $table->decimal('min_purchase', 10, 2)->default(0)->after('used');
                }
                if (!Schema::hasColumn('coupons', 'max_discount')) {
                    $table->decimal('max_discount', 10, 2)->default(0)->after('min_purchase');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('coupons')) {
            Schema::table('coupons', function (Blueprint $table) {
                $columns = ['description', 'min_purchase', 'max_discount'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('coupons', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
