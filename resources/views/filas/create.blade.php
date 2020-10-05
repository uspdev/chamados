@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

<div class="row">
    <div class="col-md-12 form-inline">

    @include('common.list-table-form')

    </div>
</div>

@endsection
