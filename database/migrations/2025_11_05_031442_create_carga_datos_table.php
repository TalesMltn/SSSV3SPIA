<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carga_datos', function (Blueprint $table) {
            $table->id();
            $table->decimal('precision_validacion', 5, 2);
            $table->decimal('precision_ia', 5, 2);
            $table->integer('total_filas');
            $table->integer('validos');
            $table->integer('errores');
            $table->string('archivo_nombre');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('carga_datos');
    }
};