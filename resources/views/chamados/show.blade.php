@extends('master')

@section('title', 'Chamado')

@section('content_header')
@endsection

@section('content')
@parent

<div class="card bg-light mb-3">
    <div class="card-header h5">
        <span class="text-muted">Chamado no.</span>
        {{ $chamado->nro }}/{{ Carbon\Carbon::parse($chamado->created_at)->format('Y') }}
        <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
        @include('chamados.partials.status')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                {{-- Informações principais --}}
                @include('chamados.show.main')
            </div>
            <div class="col-md-4">
                {{-- Painel do meio --}}
                @include('chamados.show.dados-formulario')
            </div>
            <div class="col-md-4">
                {{-- Painel direito --}}
                @include('chamados.show.atendente')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @include('chamados.show.card-comentarios')
    </div>
    <div class="col-md-4">
        @include('chamados.show.card-file-upload')
        @include('chamados.show.card-pessoas')
    </div>
    <div class="col-md-4">
        @include('chamados.show.card-vinculados')
        @includewhen(Gate::check('perfilAtendente') || Gate::check('perfilAdmin'),'chamados.show.card-anotacoes-atendente')
    </div>
</div>

@endsection
