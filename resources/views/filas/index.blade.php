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

        <span class="h4 mt-2">{{ $data->title }}</span>

        @include('common.list-table-filter-box')

        @if ($data->modal ?? true)
        @include('common.list-table-modal-btn-create')
        @else
        @include('common.list-table-btn-create')
        @endif

    </div>
</div>

@if (!$data)
<div>É necessário enviar a variável $data para que este componente funcione.</div>
@else

<?php #dd($data); ?>

<table class="table table-striped table-sm table-hover datatable-nopagination">
    <thead>
        <tr>
            <td>Setor</td>
            <td>Nome</td>
            <td>Descrição</td>
            <td>Estado</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($data->rows as $row)
        <tr>
            <td>{{ $row->setor->sigla }}</td>
            <td><a href="filas/{{ $row->id }}">{{ $row->nome }}</a></td>
            <td>{{ $row->descricao }}</td>
            <td> @include('filas.partials.status') </td>

        </tr>
        @endforeach
    </tbody>
</table>

@endif

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
