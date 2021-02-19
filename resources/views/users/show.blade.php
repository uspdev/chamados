@extends('master')

@section('content')
@parent

<div class="row">
    <div class="col-md-12">
        <div class="h4 mt-2">
            @if(Gate::check('perfiladmin'))
            <a href="users">Usuários</a> <i class="fas fa-angle-right"></i>
            @endif
            {{ $user->name }}
            @if(Gate::check('perfiladmin')) | @include('users.partials.btn-change-user')
            @endif
        </div>
        <div class="ml-2">
            <div>Nome: {{ $user->name }}</div>
            <div>email: {{ $user->email }}</div>
            <div>telefone: {{ $user->telefone }}</div>
            <div>Último login: {{ $user->last_login_at }}</div>
            <div>Admin: {{ $user->is_admin ? 'sim' : 'não' }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="h4 mt-2">Setores</div>
        <div class="ml-2">
            @forelse($user->setores as $setor)
            <div><a href="setores/#{{ strtolower($setor->sigla) }}">{{ $setor->sigla }}</a> - {{ $setor->nome }}</div>
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
            <div><a href="filas/{{ $fila->id }}">({{$fila->Setor->sigla}}) {{ $fila->nome }}</a> - {{ $fila->descricao }}</div>
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
