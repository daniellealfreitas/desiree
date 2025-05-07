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
        Schema::create('user_online_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Use string for date to avoid SQLite formatting issues
            if (config('database.default') === 'sqlite') {
                $table->string('date');
            } else {
                $table->date('date');
            }
            
            $table->integer('minutes_online')->default(0);
            $table->integer('minutes_away')->default(0);
            $table->integer('minutes_dnd')->default(0);
            $table->timestamp('last_status_change')->nullable();
            $table->string('current_status')->default('offline');
            $table->timestamps();
            
            // Índice composto para consultas rápidas por usuário e data
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_online_stats');
    }
};
