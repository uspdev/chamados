@extends('master')

@section('content')
@parent
{{-- @include('common.list-table-modal') --}}
<div class="row">
    <div class="col-md-12">

        <div class="card card-outline card-primary">
            <div class="card-header">
                <div class="card-title form-inline my-0">
                    <a href="setores">Setores</a> <i class="fas fa-angle-right mx-2"></i> ({{ $setor->sigla }}) {{ $setor->nome }} |&nbsp;


                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                    {{ $setor }}
                    </div>
                    <div class="col-md-5">
                        Pessoas
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
