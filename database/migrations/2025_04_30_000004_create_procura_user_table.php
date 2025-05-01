<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procura_user', function (Blueprint $table) {
            $table->foreignId('procura_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->primary(['procura_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procura_user');
    }
};