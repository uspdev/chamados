@extends('master')

@section('content')
@parent
@include('common.list-table-modal')

<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Filas</span>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
        @if(Gate::check('setores.viewAny'))
        @include('common.list-table-modal-btn-create')
        @endif
    </div>
</div>

<?php #dd($data); ?>

<table class="table table-striped table-hover datatable-nopagination display responsive" style="width:100%">
    <thead>
        <tr>
            <td>Setor</td>
            <td>Nome</td>
            <td>Descrição</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($filas as $fila)
        <tr>
            <td>{{ $fila->setor->sigla }}</td>
            <td>
                @include('filas.partials.status-small')
                <a class="mr-2" href="filas/{{ $fila->id }}">{{ $fila->nome }}</a>
                @include('filas.partials.status-muted')
                @if($fila->users->isEmpty())
                <span class="badge badge-danger ml-3">Sem pessoas</span>
                @endif
            </td>
            <td>{{ $fila->descricao }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.datatable-nopagination').DataTable({
            dom: 't'
            , "paging": false
        });
    })

</script>
@endsection
