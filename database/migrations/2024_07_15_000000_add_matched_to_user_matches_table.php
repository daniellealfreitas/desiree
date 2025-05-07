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
        Schema::table('user_matches', function (Blueprint $table) {
            $table->boolean('matched')->default(false)->after('liked');
            $table->timestamp('matched_at')->nullable()->after('matched');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_matches', function (Blueprint $table) {
            $table->dropColumn(['matched', 'matched_at']);
        });
    }
};
