<?php

namespace App\Mail;

use App\Site;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SiteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $site;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Site $site, User $user)
    {
        $this->site = $site;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.site')
                    ->from(config('sites.email_principal'))
                    ->to([config('sites.email_principal'),$this->user->email])
                    ->subject("Nova solicitação de site: {$this->site->dominio}" . config('sites.dnszone'));
    }
}
