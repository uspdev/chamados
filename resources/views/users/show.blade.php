@extends('master')

@section('content')
  @parent

  <div class="row">
    <div class="col-md-12">

      <div class="card mb-3">
        <div class="card-header">
          @if (Gate::check('perfiladmin'))
            <a href="users">Usuários</a> <i class="fas fa-angle-right"></i>
          @else
            Meu perfil <i class="fas fa-angle-right"></i>
          @endif
          {{ $user->name }}
          @if (Gate::check('perfiladmin'))
            | @include('users.partials.btn-change-user')
          @endif
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div><span class="text-muted">Número USP:</span> {{ $user->codpes }}</div>
              <div><span class="text-muted">Nome:</span> {{ $user->name }}</div>
              <div><span class="text-muted">email:</span> {{ $user->email }}</div>
              <div>
                <span class="text-muted">Vínculo:</span>
                {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->pivot->funcao ?? 'sem vínculo' }} -
                {{ $user->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->sigla ?? 'sem setor' }}
              </div>
              <div><span class="text-muted">telefone:</span> {{ $user->telefone }}</div>
              <div><span class="text-muted">Último login:</span> {{ $user->last_login_at }}</div>
              @if (Gate::check('perfiladmin'))
                <div><span class="text-muted">Admin:</span> {{ $user->is_admin ? 'sim' : 'não' }}</div>
              @endif
            </div>
            <div class="col-md-6">
              <div class="h5 mt-2">Setores</div>
              <div class="ml-2">
                @forelse($user->setores()->wherePivot('funcao', 'Gerente')->get() as $setor)
                  <div>
                    <a href="setores/#{{ strtolower($setor->sigla) }}">{{ $setor->sigla }}</a>
                    - {{ $setor->nome }} ({{ $setor->pivot->funcao }})
                  </div>
                @empty
                  Sem setores
                @endforelse
              </div>
              <div class="h5 mt-2">Chamados que observo (não finalizados)</div>
              <div class="ml-2">
                @forelse($user->chamados()->wherePivot('papel', 'Observador')->finalizado(false)->get() as $chamado)
                  <div>
                    <a href="chamados/{{ $chamado->id }}">
                      {{ $chamado->nro }}/{{ $chamado->created_at->year }}
                    </a>
                    - {{ $chamado->assunto }}
                    @include('chamados.partials.status')
                  </div>
                @empty
                  Nada sendo observado
                @endforelse
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      @include('users.partials.card-notificacoes')
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      @includewhen(Gate::check('perfiladmin'), 'users.partials.card-oauth')
    </div>
  </div>
@endsection
