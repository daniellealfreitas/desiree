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
        // Only add the column if the table exists and the column doesn't already exist
        if (Schema::hasTable('posts') && !Schema::hasColumn('posts', 'group_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->foreignId('group_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('posts') && Schema::hasColumn('posts', 'group_id')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropForeign(['group_id']);
                $table->dropColumn('group_id');
            });
        }
    }
};
