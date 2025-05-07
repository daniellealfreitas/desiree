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
        Schema::create('user_points_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action_type'); // 'post', 'comment', 'like', 'login', etc.
            $table->string('description')->nullable();
            $table->integer('points');
            $table->integer('total_points'); // Running total after this action
            $table->integer('ranking_position')->nullable(); // User's position in the ranking after this action
            $table->foreignId('related_id')->nullable(); // ID of the related entity (post_id, comment_id, etc.)
            $table->string('related_type')->nullable(); // Type of the related entity ('App\Models\Post', etc.)
            $table->timestamps();
        });

        // Modify the existing user_points table to add action_type
        Schema::table('user_points', function (Blueprint $table) {
            // Drop existing columns if needed
            if (Schema::hasColumn('user_points', 'points')) {
                $table->dropColumn('points');
            }
            
            // Add new columns
            $table->integer('total_points')->default(0);
            $table->integer('daily_points')->default(0);
            $table->integer('weekly_points')->default(0);
            $table->integer('monthly_points')->default(0);
            $table->integer('streak_days')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->json('achievements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points_log');
        
        // Restore the original structure of user_points
        Schema::table('user_points', function (Blueprint $table) {
            $table->dropColumn([
                'total_points',
                'daily_points',
                'weekly_points',
                'monthly_points',
                'streak_days',
                'last_activity_at',
                'achievements'
            ]);
            
            $table->integer('points')->default(0);
        });
    }
};
