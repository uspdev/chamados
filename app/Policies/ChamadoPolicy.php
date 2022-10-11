<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\Fila;
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
        return $user;
    }

    /**
     * Determine whether the user can view the chamado.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamado  $chamado
     * @return mixed
     */
    public function view(User $user, Chamado $chamado)
    {
        if (
            $this->permitePessoasChamado($user, $chamado) ||
            $this->permitePessoasFila($user, $chamado) ||
            Gate::allows('admin')
        ) {
            return true;
        }

        /* chamados vinculados -somente um nível */
        foreach ($chamado->vinculados as $vinculado) {
            /* autor, atendentes e observadores do vinculado */
            if ($this->permitePessoasChamado($user, $vinculado)) {
                return true;
            }
        }
    }

    /**
     * Determine whether the user can create chamados.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Fila $fila)
    {
        // usa a mesma regra da listagem
        $setores = Fila::listarFilasParaNovoChamado();
        foreach ($setores as $setor) {
            foreach ($setor->filas as $f) {
                if ($f->id == $fila->id) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Determine whether the user can update the chamado.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamado  $chamado
     * @return mixed
     */
    public function update(User $user, Chamado $chamado)
    {
        /* se estiver fechado não pode editar nada a menos do comentário para reabrir no outro gate */
        if ($chamado->status == 'Fechado') {
            return false;
        }

        if (
            $this->permitePessoasChamado($user, $chamado) ||
            $this->permitePessoasFila($user, $chamado) ||
            Gate::allows('admin')
        ) {
            return true;
        }

        # chamados vinculados não pode editar
    }

    /**
     * Determina se user pode atualizar comentarios
     * em especial quando o chamado está fechado mas não finalizado
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chamado  $chamado
     * @return mixed
     */
    public function updateComentario(User $user, Chamado $chamado)
    {
        if (
            $this->permitePessoasChamado($user, $chamado) ||
            $this->permitePessoasFila($user, $chamado) ||
            Gate::allows('admin')
        ) {
            # só checa o finalizado depois de autorizar as pessoas
            if (!$chamado->isFinalizado()) {
                return true;
            }
        }

        # chamados vinculados não pode editar
    }

    protected function permitePessoasChamado(User $user, Chamado $chamado)
    {
        /* autor, atendentes e observadores */
        foreach ($chamado->users as $u) {
            if ($user->codpes == $u->codpes) {
                return true;
            }
        }
    }

    public function permitePessoasFila(User $user, Chamado $chamado)
    {
        # atendentes que estão na fila.
        # NÃO está diferenciando gerente e atendente.
        foreach ($chamado->fila->users as $userFila) {
            if ($user->codpes == $userFila->codpes  && session('perfil') != 'admin') {
                return true;
            }
        }
    }
}
