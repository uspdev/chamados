@extends('master')

@section('content')
@parent
<br>
<div class="mb-2">
    <span class="h3">{{ $data->title }}</span>
    <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Novo
    </button>
</div>

@include('common.list-table')

@endsection


