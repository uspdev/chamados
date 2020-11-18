<?php

namespace App\Mail;

use App\Models\Comentario;
use App\Models\User;
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
        $emails = [];
        /* pessoas envolvidas no chamado */
        foreach ($this->chamado->users()->wherePivot('papel', '!=', 'Autor')->get() as $user) {
            $emails[] = $user->email;
        }

        // Monta título do email
        $app = config('app.name');
        $chamado_ano = $this->chamado->created_at->format('Y');
        $subject = "[{$app}] Novo comentário no chamado {$this->chamado->nro}/{$chamado_ano}";

        return $this->view('emails.comentario')
            ->from(config('mail.from.address'))
            ->to($this->autor->email)
            ->bcc($emails)
            ->subject($subject);
    }
}
