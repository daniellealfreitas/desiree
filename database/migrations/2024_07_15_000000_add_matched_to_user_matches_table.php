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
        if (Schema::hasTable('user_matches')) {
            Schema::table('user_matches', function (Blueprint $table) {
                if (!Schema::hasColumn('user_matches', 'matched')) {
                    $table->boolean('matched')->default(false)->after('liked');
                }
                if (!Schema::hasColumn('user_matches', 'matched_at')) {
                    $table->timestamp('matched_at')->nullable()->after('matched');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('user_matches')) {
            Schema::table('user_matches', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('user_matches', 'matched')) $columns[] = 'matched';
                if (Schema::hasColumn('user_matches', 'matched_at')) $columns[] = 'matched_at';

                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
