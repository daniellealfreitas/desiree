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
        Schema::table('payments', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('payments', 'sender_id')) {
                $table->foreignId('sender_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
            }

            if (!Schema::hasColumn('payments', 'message')) {
                $table->text('message')->nullable()->after('amount');
            }

            if (!Schema::hasColumn('payments', 'payment_id')) {
                $table->string('payment_id')->nullable()->after('status');
            }

            // Simply change status to string without checking type
            if (Schema::hasColumn('payments', 'status')) {
                // Drop and recreate the status column as string
                $table->dropColumn('status');
                $table->string('status')->default('pending')->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'sender_id')) {
                $table->dropForeign(['sender_id']);
                $table->dropColumn('sender_id');
            }

            if (Schema::hasColumn('payments', 'message')) {
                $table->dropColumn('message');
            }

            if (Schema::hasColumn('payments', 'payment_id')) {
                $table->dropColumn('payment_id');
            }
        });
    }
};
