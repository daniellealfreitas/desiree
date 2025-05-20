<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'sexo')) {
                $table->enum('sexo', ['casal', 'homem', 'mulher'])->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'aniversario')) {
                $table->date('aniversario')->nullable()->after('sexo');
            }
            if (!Schema::hasColumn('users', 'privado')) {
                $table->boolean('privado')->default(false)->after('aniversario');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('privado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sexo', 'aniversario', 'privado', 'bio']);
        });
    }
};