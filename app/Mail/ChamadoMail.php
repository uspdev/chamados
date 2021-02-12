<?php

namespace App\Mail;

use App\Models\Chamado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChamadoMail extends Mailable
{
    use Queueable, SerializesModels;
    use MailTrait;

    public $chamado;
    public $autor;
    public $papel;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->papel = $data['papel'];
        $this->user = $data['user'];
        $this->chamado = $data['chamado'];
        $this->autor = $this->chamado->users()->wherePivot('papel', 'Autor')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->construirAssunto())
            ->view('emails.novo_chamado');
    }
}
