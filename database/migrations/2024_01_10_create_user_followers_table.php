<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('follow_requests');
        
        Schema::create('follow_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('requested_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate requests
            $table->unique(['requester_id', 'requested_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('follow_requests');
    }
};
