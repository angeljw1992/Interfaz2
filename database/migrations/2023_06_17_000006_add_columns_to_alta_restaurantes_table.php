<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addColumnsToAltaRestaurantesTable extends Migration
{
    public function up()
    {
        Schema::table('alta_restaurantes', function (Blueprint $table) {
            $table->unsignedBiginteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8638225')->references('id')->on('teams');
        });
    }
    public function down()
    {
        Schema::table('alta_restaurantes', function ($table) {
            $table->dropColumn('team_id');
        });
    }
}
