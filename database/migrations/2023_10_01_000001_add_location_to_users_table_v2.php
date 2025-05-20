<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocationToUsersTableV2 extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable();
                $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            }

            if (!Schema::hasColumn('users', 'state_id')) {
                $table->unsignedBigInteger('state_id')->nullable();
                $table->foreign('state_id')->references('id')->on('states')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'city_id')) {
                $table->dropForeign(['city_id']);
            }

            if (Schema::hasColumn('users', 'state_id')) {
                $table->dropForeign(['state_id']);
            }

            $columns = [];
            if (Schema::hasColumn('users', 'city_id')) $columns[] = 'city_id';
            if (Schema::hasColumn('users', 'state_id')) $columns[] = 'state_id';

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
}
