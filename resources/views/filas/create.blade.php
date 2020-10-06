@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

<div class="row">
    <div class="col-md-6">
        <span class="h4 mt-2">Criar nova fila</span>

        @section('form-dismiss-btn')
        <a href="filas" class="btn btn-secondary" data-dismiss="modal">Cancelar</a>
        @endsection

        @include('common.list-table-form')

    </div>

</div>
</div>

@endsection
