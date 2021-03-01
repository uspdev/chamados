@extends('master')

@section('content')
  @parent

  <div class="row">
    <div class="col-md-12">
      <div class="h4 mt-2">
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
      <div class="ml-2">
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
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="h4 mt-2">Setores</div>
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
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="h4 mt-2">Filas</div>
      <div class="ml-2">
        @forelse($user->filas as $fila)
          <div>
            <a href="filas/{{ $fila->id }}">({{ $fila->Setor->sigla }}) {{ $fila->nome }}</a>
            - {{ $fila->descricao }} ({{ $fila->pivot->funcao }})
          </div>
        @empty
          Sem filas
        @endforelse
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="h4 mt-2">Meus chamados</div>
      <div class="ml-2">
        <a href="chamados">Veja aqui</a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="h4 mt-2">Chamados que observo</div>
      <div class="ml-2">
        @forelse($user->chamados()->wherePivot('papel', 'Observador')->get() as $chamado)
          <div>
            <a href="chamados/{{ $chamado->id }}">{{ $chamado->nro }}/{{ $chamado->created_at->year }}</a> -
            {{ $chamado->assunto }}
          </div>
        @empty
          Sem chamados
        @endforelse
      </div>
    </div>
  </div>

@endsection
