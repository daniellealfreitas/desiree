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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sexo')) {
                $table->string('sexo')->nullable();
                $table->check('sexo IS NULL OR sexo IN (\'Homem\', \'Mulher\', \'Casal\')');
            }
            if (!Schema::hasColumn('users', 'aniversario')) {
                $table->date('aniversario')->nullable();
            }
            if (!Schema::hasColumn('users', 'privado')) {
                $table->boolean('privado')->default(false);
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sexo', 'aniversario', 'privado', 'bio']);
        });
    }
};
