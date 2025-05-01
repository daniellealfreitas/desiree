<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // In SQLite, we need to recreate the table to remove a check constraint
        // First, backup the data
        $users = DB::table('users')->get();
        
        // Drop the column with constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sexo');
        });
        
        // Recreate the column without constraint
        Schema::table('users', function (Blueprint $table) {
            $table->string('sexo')->nullable();
        });
        
        // Restore the data
        foreach ($users as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['sexo' => $user->sexo ?? null]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // If needed to rollback, we'll add back the constraint
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sexo');
            $table->string('sexo')->nullable();
            $table->check('sexo IS NULL OR sexo IN (\'Homem\', \'Mulher\', \'Casal\')');
        });
    }
};
