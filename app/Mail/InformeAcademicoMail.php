<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class InformeAcademicoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $rutaPdf;
    public $validos;
    public $errores;

    /**
     * Crea una nueva instancia del mensaje.
     */
    public function __construct($rutaPdf, $validos, $errores)
    {
        $this->rutaPdf = $rutaPdf;
        $this->validos = $validos;
        $this->errores = $errores;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informe Académico - Universidad Continental',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.informe',
            with: [
                'validos' => $this->validos,
                'errores' => $this->errores,
                'fecha' => now()->format('d/m/Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->rutaPdf)
                ->as('Informe_Academico.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
