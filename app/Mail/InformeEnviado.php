<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InformeEnviado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $ruta;
    public $validos;
    public $errores;

    /**
     * Crear una nueva instancia del mensaje.
     *
     * @param string $ruta
     * @param array $validos
     * @param int $errores
     */
    public function __construct($ruta, $validos, $errores)
    {
        $this->ruta = $ruta;
        $this->validos = $validos;
        $this->errores = $errores;
    }

    /**
     * Construir el mensaje.
     */
    public function build()
    {
        return $this->subject('Informe académico enviado')
                    ->view('emails.informe_enviado')
                    ->attach($this->ruta);
    }
}
