@extends('master')

@section('styles')
@parent
<style>
    #card-principal {
        border: 1px solid blue;
    }

    .bg-principal {
        background-color: LightBlue;
        border-top: 3px solid blue;
    }
</style>
@endsection

@section('content')
@parent

<div class="card bg-light mb-3" id="card-principal">
    <div class="card-header bg-principal">
        <span class="text-muted">Chamado no.</span>
        {{ $chamado->nro }}/{{ Carbon\Carbon::parse($chamado->created_at)->format('Y') }}
        <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
        @include('chamados.partials.status')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6">
                        {{-- Informações principais --}}
                        @include('chamados.show.main')
                    </div>
                    <div class="col-md-6">
                        {{-- formulario--}}
                        @includewhen(!empty($template),'chamados.show.dados-formulario')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @include('chamados.show.card-comentarios-user')
                    </div>
                    <div class="col-md-6">
                        @include('chamados.show.card-arquivos')
                        @include('chamados.show.card-vinculados')
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                @includewhen(Gate::check('perfilAtendente') || Gate::check('perfilAdmin'),'chamados.show.card-atendente')
                @include('chamados.show.card-pessoas')
                @include('chamados.show.card-comentarios-system')
            </div>
        </div>

    </div>
</div>


@endsection
