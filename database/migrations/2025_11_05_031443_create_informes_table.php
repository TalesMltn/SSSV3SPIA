<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('carga_datos_id');
            $table->string('ruta_pdf');
            $table->string('ruta_excel')->nullable();
            $table->timestamp('generado_en');
            $table->boolean('enviado_por_correo')->default(false);
            $table->string('destinatario_email')->nullable();
            $table->enum('estado', ['generado', 'enviado', 'fallido'])->default('generado');
            $table->timestamps();

            $table->foreign('carga_datos_id')->references('id')->on('carga_datos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('informes');
    }
};