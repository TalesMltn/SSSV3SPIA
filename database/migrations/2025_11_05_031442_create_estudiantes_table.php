<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo');
            $table->foreignId('asignatura_id')->constrained('asignaturas')->onDelete('cascade');
            $table->decimal('calificacion', 5, 2);
            $table->integer('asistencia');
            $table->string('riesgo_predicho')->nullable(); // 'riesgo' o 'aprobado'
            $table->foreignId('carga_datos_id')
      ->nullable()
      ->constrained('carga_datos')
      ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estudiantes');
    }
};
