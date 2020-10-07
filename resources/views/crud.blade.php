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

@include('common.list-table')
@endsection
