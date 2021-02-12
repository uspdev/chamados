<?php

namespace App\Observers;

use App\Mail\ChamadoMail;
use App\Models\Chamado;

class ChamadoObserver
{
    /**
     * Handle the Chamado "created" event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function created(Chamado $chamado)
    {
        // Vamos enviar email para o autor
        $papel = 'Autor do chamado';
        $user = \Auth::user();
        \Mail::to($user->email)
            ->queue(new ChamadoMail(compact('papel', 'user', 'chamado')));

        // e para as pessoas da fila (atendentes e gerentes)
        foreach ($chamado->fila->users()->get() as $user) {
            $papel = $user->pivot->funcao . ' da fila (' . $chamado->fila->setor->sigla . ') ' . $chamado->fila->nome;
            \Mail::to($user->email)
                ->queue(new ChamadoMail(compact('papel', 'user', 'chamado')));
        }
    }

    /**
     * Handle the Chamado "updated" event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function updated(Chamado $chamado)
    {
        //
    }

    /**
     * Handle the Chamado "deleted" event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function deleted(Chamado $chamado)
    {
        //
    }

    /**
     * Handle the Chamado "restored" event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function restored(Chamado $chamado)
    {
        //
    }

    /**
     * Handle the Chamado "force deleted" event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function forceDeleted(Chamado $chamado)
    {
        //
    }
}
