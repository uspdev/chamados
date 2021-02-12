<?php

namespace App\Mail;

use App\Models\Comentario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComentarioMail extends Mailable
{
    use MailTrait;
    use Queueable, SerializesModels;

    public $comentario;
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
        $this->comentario = $data['comentario'];
        $this->chamado = $this->comentario->chamado;
        $this->autor = $this->chamado->users()->wherePivot('papel', 'Autor')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject($this->construirAssunto())
            ->view('emails.novo_comentario');
    }
}
