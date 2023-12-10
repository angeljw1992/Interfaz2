<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addColumnsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBiginteger('team_id')->nullable();
            $table->enum("type", ["admin", "member"])->default("admin");
            $table->foreign('team_id', 'team_fk_1638225')->references('id')->on('teams');
        });
    }

    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('team_id');
            $table->dropColumn('type');
        });
    }
}
