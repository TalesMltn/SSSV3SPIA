<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\InformeEnviado;
use App\Models\Informe;
use App\Mail\InformeAcademicoMail;

class EnviarInformeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $informeId;
    protected $email;

    public function __construct($informeId, $email)
    {
        $this->informeId = $informeId;
        $this->email = $email;
    }

    public function handle()
    {
        $informe = Informe::find($this->informeId);
        $rutaPdf = storage_path("app/public/{$informe->ruta_pdf}");
    
        // Si el informe tiene relación con la carga
        $carga = $informe->cargaDatos; // Asegúrate que la relación exista en el modelo Informe
    
        if (file_exists($rutaPdf)) {
            $validos = $carga->validos ?? 0;
            $errores = $carga->errores ?? 0;
    
            Mail::to($this->email)->send(new InformeEnviado($rutaPdf, $validos, $errores));
        }
    }
}    