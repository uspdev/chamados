@extends('master')

<?php #dd($data) ?>
<?php
$rows = $users;
$fields = \App\Models\User::getFields();
?>
@section('content')
@parent

<div class="row">
    <div class="col-md-12 form-inline">

        <span class="h4 mt-2">Usuários</span>
        @include('partials.datatable-filter-box')
        @include('users.partials.btn-add-modal')

    </div>
</div>

@include('partials.datatable-list', ['btns'=>['users.partials.btn-change-user','partials.btn-delete']])

@endsection
