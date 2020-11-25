<?php
/*
View padrão para crud.
Controller:
  utiliza ResourceTrait.php
  deve definir $data
    protected $data = [
        'title' => 'Filas',
        'url' => 'filas', // caminho da rota do resource
        'modal' => false,
        'showId' => true,
        'editBtn' => true,
        'viewBtn' => false,

    ];
Model:
  deve definir fields
  pode definir rules

*/
?>
@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

@if ($data->modal ?? true)
{{-- incluindo o modal com form  --}}
@include('common.list-table-modal')
@endif

<div class="row">
    <div class="col-md-12 form-inline">

        <span class="h4 mt-2">Filas</span>

        @include('common.list-table-filter-box')

        @include('common.list-table-modal-btn-create')
        {{-- @include('common.list-table-btn-create') --}}

    </div>
</div>

<?php #dd($data); ?>

<table class="table table-striped table-hover datatable-nopagination">
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
