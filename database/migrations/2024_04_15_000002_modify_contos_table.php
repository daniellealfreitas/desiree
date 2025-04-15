<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contos', function (Blueprint $table) {
            // Drop the existing category column if it exists
            if (Schema::hasColumn('contos', 'category')) {
                $table->dropColumn('category');
            }
            
            // Add the new category_id column if it doesn't exist
            if (!Schema::hasColumn('contos', 'category_id')) {
                $table->foreignId('category_id')->after('slug')->nullable()->constrained('contos_categorias');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contos', function (Blueprint $table) {
            if (Schema::hasColumn('contos', 'category_id')) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            }
            
            if (!Schema::hasColumn('contos', 'category')) {
                $table->string('category')->after('slug');
            }
        });
    }
};