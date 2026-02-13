<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;

    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(Ticket $ticket, $userName)
    {
        $this->ticket = $ticket;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Ticket Creado Exitosamente - # '.$this->ticket->id)
            ->view('emails.ticket-created')
            ->with([
                'ticket' => $this->ticket,
                'userName' => $this->userName,
            ]);
    }
}
