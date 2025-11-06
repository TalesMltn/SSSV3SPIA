<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carga_datos', function (Blueprint $table) {
            $table->unsignedBigInteger('asignatura_id')->after('usuario_id')->nullable();
            $table->foreign('asignatura_id')
                  ->references('id')
                  ->on('asignaturas')
                  ->onDelete('set null'); // o 'cascade' si prefieres
        });
    }

    public function down()
    {
        Schema::table('carga_datos', function (Blueprint $table) {
            $table->dropForeign(['asignatura_id']);
            $table->dropColumn('asignatura_id');
        });
    }
};