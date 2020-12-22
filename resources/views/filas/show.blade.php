@extends('master')

@section('styles')
@parent
<style>
    .disable-links {
        pointer-events: none;
    }

</style>
@endsection

@section('content')
@parent

@include('common.list-table-modal')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title form-inline my-0">
                    <a href="filas">Filas</a> <i class="fas fa-angle-right mx-2"></i> ({{ $fila->setor->sigla }}) {{ $fila->nome }} | &nbsp;

                    @if($fila->estado != 'Desativada')
                    @include('common.list-table-btn-edit', ['row'=>$fila]) &nbsp; | &nbsp;
                    @endif

                    @include('filas.partials.enable-disable-btn')
                </div>
            </div>

            <div class="card-body {{ $fila->estado == 'Desativada' ? 'disable-links': '' }}">
                <div class="row">
                    <div class="col-md-7">
                        {{-- Principal --}}
                        <span class="text-muted">Setor:</span> {{ $fila->setor->sigla }}<br>
                        <span class="text-muted">Nome:</span> {{ $fila->nome }}<br>
                        <span class="text-muted">Descrição:</span> {{ $fila->descricao }}<br>
                        <br>
                        @include('filas.partials.config')
                        <br>
                        @include('filas.partials.formulario')
                    </div>
                    <div class="col-md-5">
                        {{-- Secundário --}}
                        @include('filas.partials.pessoas')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
