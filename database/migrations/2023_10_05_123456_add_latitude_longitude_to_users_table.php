<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitudeToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('level');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}