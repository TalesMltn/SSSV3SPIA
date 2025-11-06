<?php

use App\Http\Controllers\CargaController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\EnvioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\EstudianteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {

    // 📦 CARGAS
    Route::get('/cargas', [CargaController::class, 'index'])->name('cargas.index');
    Route::get('/cargas/create', [CargaController::class, 'create'])->name('cargas.create');
    Route::post('/cargas', [CargaController::class, 'store'])->name('cargas.store');

    // ⚠️ CORREGIDO: esta ruta estaba mal cerrada
    Route::get('/carga', function() {
        return redirect()->route('cargas.index');
    });

    // ✅ RUTA CORRECTA DE ESTUDIANTES
    // Mostrar lista de estudiantes
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');

    // Guardar nuevo estudiante
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::post('/estudiantes', [EstudianteController::class, 'store'])->name('estudiantes.store');
    Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');



    // 📊 INFORMES
    Route::get('/informes/{id}', [InformeController::class, 'show'])->name('informes.show');
    Route::get('/informes/generar/{carga_id}', [InformeController::class, 'generar'])->name('informes.generar');
    Route::get('/informes/descargar/{id}', [InformeController::class, 'descargar'])->name('informes.descargar');

    // 📤 ENVIOS
    Route::get('/envios/formulario/{carga_id}', [EnvioController::class, 'formulario'])->name('envios.formulario');
    Route::post('/envios/enviar/{carga_id}', [EnvioController::class, 'enviar'])->name('envios.enviar');

    // 👤 PERFIL
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 📤 EXPORTACIONES
    Route::get('/export/excel', [ExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'exportPDF'])->name('export.pdf');

    // routes/web.php
    Route::get('/export/excel', [ExportController::class, 'excel'])->name('export.excel');
    Route::get('/export/pdf', [ExportController::class, 'pdf'])->name('export.pdf');


    // 📋 DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
