<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'last_seen')) {
                $table->timestamp('last_seen')->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('offline')->after('last_seen');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'last_seen')) $columns[] = 'last_seen';
            if (Schema::hasColumn('users', 'status')) $columns[] = 'status';

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};