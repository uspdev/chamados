<?php

namespace App\Mail;

use App\User;
use App\Comentario;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

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
        // emails dos envolvidos nos comentários
        // quem abriu o chamado sempre recebe email
        $emails = [$this->comentario->chamado->user->email];
        
        // Responsável pelo site
        $codpes = $this->comentario->chamado->site->owner;
        $owner = User::where('codpes', $codpes)->first();
        if ($owner) {
            $emails[] = $owner->email;
        }
        
        foreach($this->comentario->chamado->comentarios as $comment){
            $emails[] = $comment->user->email;
        }
        $emails = array_unique($emails);
    
        // Monta título do email
        if($this->comentario->chamado->status == 'fechado' ) {
            $subject = "Chamado #{$this->comentario->id} fechado ({$this->comentario->chamado->site->dominio}" . config('sites.dnszone') . ")";
        } else {
            $subject = "Novo comentário no chamado #{$this->comentario->id} ({$this->comentario->chamado->site->dominio}" . config('sites.dnszone') . ")";
        }

        return $this->view('emails.comentario')
                    ->from(config('sites.email_principal'))
                    ->to(config('sites.email_principal'))
                    ->bcc($emails)
                    ->subject($subject);
    }
}
