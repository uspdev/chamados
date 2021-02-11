<?php

namespace App\Observers;

use App\Mail\ComentarioMail;
use App\Models\Comentario;

class ComentarioObserver
{
    /**
     * Handle the Comentario "created" event.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function created(Comentario $comentario)
    {
        /* envia para cada pessoa envolvida no chamado */
        foreach ($comentario->chamado->users()->get() as $user) {
            #dd($user->pivot->papel);
            \Mail::to($user->email)
                ->queue(new ComentarioMail($comentario));
        }
        // rodar a fila
        /*
    Atendentes e gerentes
    novo chamado - pessoas da fila (s)
    atualização de outros chamados (pessoas da fila)(s)

    Usuário comum
    atualizacao de chamados que participa (s)

    guardar log de tudo
     */

    }

    /**
     * Handle the Comentario "updated" event.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function updated(Comentario $comentario)
    {
        //
    }

    /**
     * Handle the Comentario "deleted" event.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function deleted(Comentario $comentario)
    {
        //
    }

    /**
     * Handle the Comentario "restored" event.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function restored(Comentario $comentario)
    {
        //
    }

    /**
     * Handle the Comentario "force deleted" event.
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function forceDeleted(Comentario $comentario)
    {
        //
    }
}
