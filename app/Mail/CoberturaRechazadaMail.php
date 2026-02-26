<?php

namespace App\Mail;

use App\Models\TurnoRodado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CoberturaRechazadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $turno;

    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(TurnoRodado $turno, $userName)
    {
        $this->turno = $turno;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $this->turno->load(['rodado', 'taller']);

        return $this->subject('Cobertura Rechazada - Vehículo '.($this->turno->rodado->patente ?? 'Sin patente'))
            ->view('emails.cobertura-rechazada')
            ->with([
                'turno' => $this->turno,
                'userName' => $this->userName,
            ]);
    }
}
