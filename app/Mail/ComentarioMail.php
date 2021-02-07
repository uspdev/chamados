<?php

namespace App\Mail;

use App\Models\Comentario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComentarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $comentario;
    public $chamado;
    public $autor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comentario $comentario)
    {
        $this->comentario = $comentario;
        $this->chamado = $comentario->chamado;
        $this->autor = $this->chamado->users()->wherePivot('papel', 'Autor')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // Monta subject do email
        $app = config('app.name');
        $chamado_ano = $this->chamado->created_at->format('Y');
        $subject = "[{$app} {$this->chamado->nro}/{$chamado_ano}] Novo comentário";

        return $this->from(config('mail.from.address'), config('mail.from.name'))
        #->to($this->autor->email)
        #->bcc($emails)
            ->subject($subject)
            ->view('emails.comentario');
    }
}
