<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('sexo', ['casal', 'homem', 'mulher'])->nullable()->after('username');
            $table->date('aniversario')->nullable()->after('sexo');
            $table->boolean('privado')->default(false)->after('aniversario');
            $table->text('bio')->nullable()->after('privado');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sexo', 'aniversario', 'privado', 'bio']);
        });
    }
};