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
        Schema::table('user_cover_photos', function (Blueprint $table) {
            $table->integer('crop_x')->nullable()->after('photo_path');
            $table->integer('crop_y')->nullable()->after('crop_x');
            $table->integer('crop_width')->nullable()->after('crop_y');
            $table->integer('crop_height')->nullable()->after('crop_width');
            $table->string('cropped_photo_path')->nullable()->after('crop_height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_cover_photos', function (Blueprint $table) {
            $table->dropColumn(['crop_x', 'crop_y', 'crop_width', 'crop_height', 'cropped_photo_path']);
        });
    }
};
