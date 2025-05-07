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
        Schema::table('carts', function (Blueprint $table) {
            if (!Schema::hasColumn('carts', 'session_id')) {
                $table->string('session_id')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('carts', 'total')) {
                $table->decimal('total', 10, 2)->default(0)->after('session_id');
            }
            
            if (!Schema::hasColumn('carts', 'coupon_id')) {
                $table->foreignId('coupon_id')->nullable()->after('total')->constrained()->onDelete('set null');
            }
            
            if (!Schema::hasColumn('carts', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0)->after('coupon_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $columns = ['session_id', 'total', 'coupon_id', 'discount'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('carts', $column)) {
                    if ($column === 'coupon_id') {
                        $table->dropForeign(['coupon_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};
