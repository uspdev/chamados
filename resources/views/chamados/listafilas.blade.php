@extends('master')

@section('content')
@parent

<div class="row">
    <div class="col-md-12 form-inline">
        <span class="h4 mt-2">Novo chamado</span>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
    </div>
</div>

<table class="table table-sm table-hover novo-chamado">
    <thead>
        <tr>
            <td><span class="d-none">Filas</span></td>
        </tr>
    </thead>
    <tbody>
        @foreach ($setores as $setor)
        @if($setor->filas->count())
        <tr>
            <td>
                {{$setor->sigla}}
                @foreach ($setor->filas as $fila)
                <div class="ml-3">
                    <a href="chamados/create/{{$fila['id']}}">{{$fila->nome}}</a> - {{$fila->descricao}}
                </div>
                @endforeach
                <br>
            </td>
        </tr>
        @endif
        @endforeach
    </tbody>
</table>


@endsection

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.novo-chamado').DataTable({
            dom: 't'
            , "paging": false
            , "sort": false
        });

    })

</script>
@endsection
