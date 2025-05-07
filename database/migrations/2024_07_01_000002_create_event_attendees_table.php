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
        Schema::create('event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('registered'); // registered, confirmed, attended, cancelled
            $table->string('ticket_code')->nullable();
            $table->string('payment_status')->default('pending'); // pending, completed, failed, refunded
            $table->string('payment_method')->nullable();
            $table->string('payment_id')->nullable();
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
            
            // Ensure a user can only register once for an event
            $table->unique(['event_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendees');
    }
};
