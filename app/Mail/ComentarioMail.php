<?php

namespace App\Mail;

use App\User;
use App\Comentario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Uspdev\Replicado\Pessoa;

class ComentarioMail extends Mailable
{
    use Queueable, SerializesModels;

    public $comentario;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comentario $comentario, User $user)
    {
        $this->comentario = $comentario;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* email de quem abriu o chamado */
        $codpes = $this->comentario->chamado->user->codpes;
        $email = Pessoa::emailusp($codpes);
        $emails = [$email];

        /* pessoas envlvidas nos comentários */
        foreach($this->comentario->chamado->comentarios as $comment){
            $emails[] = $comment->user->email;
        }
        $emails = array_unique($emails);

        // Monta título do email
        $subject = "Novo comentário no chamado #{$this->comentario->chamado->id}";

        return $this->view('emails.comentario')
                    ->from(config('mail.username'))
                    ->to($email)
                    ->bcc($emails)
                    ->subject($subject);
    }
}
