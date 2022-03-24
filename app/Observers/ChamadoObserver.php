<?php

namespace App\Observers;

use App\Mail\ChamadoMail;
use App\Models\Chamado;

class ChamadoObserver
{
    /**
     * Handle the Chamado "created" event.
     *
     * Ao criar um chamado ele deve ser enviado para o autor.
     * deve verificar triagem:
     * se triagem=1, deve enviar para os gerentes;
     * se triagem=0, deve enviar para todos da fila;
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
        $fila = $chamado->fila;
        if ($fila->config->triagem == 1) {
            $users = $fila->users()->wherePivot('funcao', 'Gerente')->get();
        } else {
            $users = $fila->users()->get();
        }
        foreach ($users as $user) {
            // vamos verificar se o atendente/gerente quer receber essa notificação, padrão é sim
            if (data_get($user->config, 'notifications.email.filas.' . $fila->id, true)) {
                $papel = $user->pivot->funcao . ' da fila (' . $fila->setor->sigla . ') ' . $fila->nome;
                \Mail::to($user->email)
                    ->queue(new ChamadoMail(compact('papel', 'user', 'chamado')));
            }
        }
    }

    /**
     * Listen to the Chamado updating event.
     *
     * @param  \App\Models\Chamado  $chamado
     * @return void
     */
    public function updating(Chamado $chamado)
    {
        if ($chamado->isDirty('status')) {
            // o status mudou
            //$new_email = $user->email;
            //$old_email = $user->getOriginal('email');
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
