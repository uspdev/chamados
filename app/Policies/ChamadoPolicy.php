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
        // Quem pode ver: o autor, atendentes ou admin
        if($user->codpes == $chamado->user->codpes){
            return true;               
        }
        if(Gate::allows('admin')){
            return true;
        }
        if($user->codpes == $chamado->atribuido_para){
            return true;               
        }

        $atendentes = explode(',', config('chamados.atendentes'));
        return in_array($user->codpes, $atendentes);
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
        // Quem pode ver: o autor, atendentes ou admin
        if($user->codpes == $chamado->user->codpes){
            return true;               
        }
        if(Gate::allows('admin')){
            return true;
        }
        if($user->codpes == $chamado->atribuido_para){
            return true;               
        }

        $atendentes = explode(',', config('chamados.atendentes'));
        return in_array($user->codpes, $atendentes);
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
