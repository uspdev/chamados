@extends('master')

@section('content')
    @parent

    <div class="row">
        <div class="col-md-12">
            <div class="h4 mt-2"><a href="users">Usuários</a> <i class="fas fa-angle-right"></i> {{ $user->name }}</div>
            <div>Nome: {{ $user->name }}</div>
            <div>email: {{ $user->email }}</div>
            <div>telefone: {{ $user->telefone }}</div>
            <div>Último login: {{ $user->last_login_at }}</div>
            <div>Admin: {{ $user->is_admin ? 'sim' : 'não' }}</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="h4 mt-2">Setores</div>
            @forelse($user->setores as $setor)
                <div><a href="setores/#{{ strtolower($setor->sigla) }}">{{ $setor->sigla }}</a> - {{ $setor->nome }}</div>
            @empty
                Sem setores
            @endforelse
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="h4 mt-2">Filas</div>
            @forelse($user->filas as $fila)
                <div><a href="filas/{{ $fila->id }}">({{$fila->Setor->sigla}}) {{ $fila->nome }}</a> - {{ $fila->descricao }}</div>
            @empty
                Sem filas
            @endforelse
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="h4 mt-2">Meus chamados</div>
            @forelse($user->chamados()->wherePivot('papel', 'Autor')->get() as $chamado)
                <div><a href="chamados/{{ $chamado->id }}">{{ $chamado->nro }}/{{ $chamado->created_at->year }}</a> -
                    {{ $chamado->assunto }}</div>
            @empty
                Sem chamados
            @endforelse
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="h4 mt-2">Chamados que observo</div>
            @forelse($user->chamados()->wherePivot('papel', 'Observador')->get() as $chamado)
                <div><a href="chamados/{{ $chamado->id }}">{{ $chamado->nro }}/{{ $chamado->created_at->year }}</a> -
                    {{ $chamado->assunto }}</div>
            @empty
                Sem chamados
            @endforelse
        </div>
    </div>

@endsection
