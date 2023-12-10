<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAltaEmpleadosTable extends Migration
{
    public function up()
    {
        Schema::table('alta_empleados', function (Blueprint $table) {
            $table->unsignedBigInteger('local_id')->nullable();
            $table->foreign('local_id', 'local_fk_8638225')->references('id')->on('alta_restaurantes');
        });
    }
}
