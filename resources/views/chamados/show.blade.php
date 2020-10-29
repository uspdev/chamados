@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
@parent
<script>
    //CKEDITOR.replace('comentario');

</script>
@stop

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
                @include('chamados.show.observacao')
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @include('chamados.show.comentarios-card')
    </div>
    <div class="col-md-4">
        @include('chamados.show.file-upload-card')
        @include('chamados.show.pessoas')
    </div>
    <div class="col-md-4">
        @include('chamados.show.vinculados')
    </div>
</div>

@stop
