<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAltaRestaurantesTable extends Migration
{
    public function up()
    {
        Schema::create('alta_restaurantes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_local')->unique();
            $table->string('nombre_local');
            $table->string('ips');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
