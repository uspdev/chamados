<?php

namespace App\Policies;

use App\Models\Fila;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;

class FilaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        # se o usuário pertence a alguma fila
        if ($user->filas()->wherePivot('funcao', 'Gerente')->count()) {
            return true;
        }

        # se o usuário é gerente de algum setor
        if ($user->setores()->wherePivot('funcao', 'Gerente')->count()) {
            return true;
        }

        # para admins
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fila  $fila
     * @return mixed
     */
    public function view(User $user, Fila $fila)
    {
        # gerentes e atendentes da fila
        foreach ($fila->users as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        # gerentes do setor
        $setor = $fila->setor;
        foreach ($setor->users()->wherePivot('funcao','Gerente')->get() as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        # gerentes do setor pai
        # na estrutura do replicado da EESC
        # tem somente 2 níveis abaixo da unidade
        if ($setor = $setor->setor) {
            foreach ($setor->users()->wherePivot('funcao','Gerente')->get() as $u) {
                if ($user->codpes == $u->codpes) {
                    return true;
                }
            }
        }

        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     * A criação de fila é dependente do setor
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user, Setor $setor)
    {
        # igual ao setores.view, pois o gerente do setor tem autonomia sobre as filas
        # gerentes do setor
        foreach ($setor->users()->wherePivot('funcao', 'Gerente')->get() as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        # gerentes do setor pai
        # na estrutura do replicado da EESC
        # tem somente 2 níveis abaixo da unidade
        if ($setor = $setor->setor) {
            foreach ($setor->users()->wherePivot('funcao', 'Gerente')->get() as $u) {
                if ($user->codpes == $u->codpes) {
                    return true;
                }
            }
        }

        /* admin */
        if (Gate::allows('perfiladmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fila  $fila
     * @return mixed
     */
    public function update(User $user, Fila $fila)
    {
        # se for gerente da fila
        foreach ($fila->users()->wherePivot('funcao', 'Gerente')->get() as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }

        # se for gerente do setor ascendente
        return $this->create($user, $fila->setor);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fila  $fila
     * @return mixed
     */
    public function delete(User $user, Fila $fila)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fila  $fila
     * @return mixed
     */
    public function restore(User $user, Fila $fila)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Fila  $fila
     * @return mixed
     */
    public function forceDelete(User $user, Fila $fila)
    {
        //
    }
}
