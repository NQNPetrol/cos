<?php

namespace App\Mail;

use App\Models\AlertaAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertaNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $alerta;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(AlertaAdmin $alerta, $userName)
    {
        $this->alerta = $alerta;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Alerta: ' . $this->alerta->titulo)
                    ->view('emails.alerta-notification')
                    ->with([
                        'alerta' => $this->alerta,
                        'userName' => $this->userName,
                    ]);
    }
}
