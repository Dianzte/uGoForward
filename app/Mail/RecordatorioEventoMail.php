<?php

namespace App\Mail;

use App\Models\CalendarioTarea;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioEventoMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Crea una nueva instancia del Mailable.
     *
     * @param CalendarioTarea $tarea  La tarea/evento para la que se envía el recordatorio.
     */
    public function __construct(
        public readonly CalendarioTarea $tarea
    ) {}

    /**
     * Define el sobre del correo (asunto y remitente).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "⏰ Recordatorio: {$this->tarea->titulo}",
        );
    }

    /**
     * Define el contenido del correo (vista Blade y datos).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.recordatorio',
        );
    }

    /**
     * Archivos adjuntos del correo.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
