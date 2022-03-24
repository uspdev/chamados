<?php

namespace App\Observers;

use App\Mail\ComentarioMail;
use App\Models\Comentario;

class ComentarioObserver
{
    /**
     * Handle the Comentario "created" event.
     * 
     * Ao criar um comentário ele deve ser enviado para pessoas listadas no chamado.
     * Caso não tenha atendente atribuído deve verificar triagem:
     * se triagem=1, deve enviar para os gerentes;
     * se triagem=0, deve enviar para todos da fila;
     *
     * @param  \App\Models\Comentario  $comentario
     * @return void
     */
    public function created(Comentario $comentario)
    {
        // envia para cada pessoa envolvida no chamado
        foreach ($comentario->chamado->users()->get() as $user) {
            #tentei passar $user no ComentarioMail mas não estava conseguindo extrair o papel no blade
            #masaki, 2/2021
            $papel = $user->pivot->papel . ' do chamado';
            \Mail::to($user->email)
                ->queue(new ComentarioMail(compact('papel', 'user', 'comentario')));
        }

        // enquanto não houver atendente atribuido envia email para todos da fila
        if ($comentario->chamado->users()->wherePivot('papel', 'Atendente')->count() == 0) {
            $fila = $comentario->chamado->fila;
            if ($fila->config->triagem == 1) {
                $users = $fila->users()->wherePivot('funcao', 'Gerente')->get();
            } else {
                $users = $fila->users()->get();
            }
            foreach ($users as $user) {
                // desde que ele aceite receber as notificacoes
                if (data_get($user->config, 'notifications.email.filas.' . $fila->id, true)) {
                    $papel = $user->pivot->funcao . ' da fila (' . $fila->setor->sigla . ') ' . $fila->nome;
                    \Mail::to($user->email)
                        ->queue(new ComentarioMail(compact('papel', 'user', 'comentario')));
                }
            }
        }

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
