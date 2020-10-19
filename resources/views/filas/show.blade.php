@extends('master')

<?php 
#dd($data->row->user);
$data->model = 'App\Models\Fila';
?>

@section('content')
@parent
@include('common.list-table-modal')
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title h5 form-inline my-0">
                    <a href="filas">Filas</a> <i class="fas fa-angle-right mx-2"></i> ({{ $data->row->setor->sigla }}) {{ $data->row->nome }} |&nbsp;
                    @include('common.list-table-btn-edit', ['row'=>$data->row]) &nbsp;|&nbsp;

                    @include('filas.partials.enable-disable-btn')

                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        @include('filas.partials.principal')
                    </div>
                    <div class="col-md-5">
                        @include('filas.partials.pessoas')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
