@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

<div class="row">
    <div class="col-md-12 form-inline">

    <?php print_r($data->row->getAttributes()); ?>

    </div>
</div>

@endsection
