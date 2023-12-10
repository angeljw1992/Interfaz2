<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAltaEmpleadosTable extends Migration
{
    public function up()
    {
        Schema::create('alta_empleados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_empleado');
            $table->string('nombre_empleado');
            $table->string('role');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
