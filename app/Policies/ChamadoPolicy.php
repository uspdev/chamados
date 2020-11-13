<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class ChamadoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any chamados.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the chamado.
     *
     * @param  \App\User  $user
     * @param  \App\Chamado  $chamado
     * @return mixed
     */
    public function view(User $user, Chamado $chamado)
    {
        /* autor, atendentes e observadores */
        foreach ($chamado->users as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        # atendentes que estão na fila.
        # NÃO está diferenciando gerente e atendente. Pode ser relevante em caso de triagem
        $fila = $chamado->fila;
        foreach ($fila->users as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        /* chamados vinculados -somente um nível */
        foreach ($chamado->vinculados as $vinculado) {
            /* autor, atendentes e observadores do vinculado */
            foreach ($vinculado->users as $u) {
                if ($user->codpes == $u->codpes) {
                    return true;
                }
            }
        }

        /* admin */
        if (Gate::allows('perfilAdmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create chamados.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the chamado.
     *
     * @param  \App\User  $user
     * @param  \App\Chamado  $chamado
     * @return mixed
     */
    public function update(User $user, Chamado $chamado)
    {
        # em principio vamos deixar igual ao view
        # mas vinculados seria acesso leitura somente
        return SELF::view($user, $chamado);

        foreach ($chamado->users as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }
        if (Gate::allows('admin')) {
            return true;
        }
        return false;
        #$atendentes = explode(',', config('chamados.atendentes'));
        #return in_array($user->codpes, $atendentes);
    }

    /**
     * Determine whether the user can delete the chamado.
     *
     * @param  \App\User  $user
     * @param  \App\Chamado  $chamado
     * @return mixed
     */
    public function delete(User $user, Chamado $chamado)
    {
        //
    }

    /**
     * Determine whether the user can restore the chamado.
     *
     * @param  \App\User  $user
     * @param  \App\Chamado  $chamado
     * @return mixed
     */
    public function restore(User $user, Chamado $chamado)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the chamado.
     *
     * @param  \App\User  $user
     * @param  \App\Chamado  $chamado
     * @return mixed
     */
    public function forceDelete(User $user, Chamado $chamado)
    {
        //
    }
}
