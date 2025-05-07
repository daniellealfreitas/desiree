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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->enum('privacy', ['public', 'private', 'secret'])->default('public');
            $table->boolean('posts_require_approval')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('members_count')->default(1); // Creator is the first member
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
