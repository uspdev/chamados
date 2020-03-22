@extends('master')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@parent

@canany(['admin', 'atendente'])

<div class="panel panel-default">
  <div class="panel-body">
    <form method="get" action="/buscaid">
        <div class="form-row">
            <div class="col-2">
              <input type="text" class="form-control" placeholder="Id ..." name="id">
            </div>
            <div class="col">
              <span class="input-group-btn">
                  <button type="submit" class="btn btn-success"> Buscar </button>
              </span>
            </div>
        </div>
    </form>
  </div>
</div>
<br>
@endcanany

@isset($chamado->id)
  @section('styles')
  @parent
  <style>
      table {
          table-layout: fixed;
          word-wrap: break-word;
      }
  </style>
  @stop

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th style="width: 50%">Detalhes</th>
          <th style="width: 50%">Chamado</th>
        </tr>
      </thead>
      <tbody>
        <tr>
         <td>
          @include('chamados/partials/chamado')
        </td>
        <td> <a href="/chamados/{{$chamado->id}}"> {!! $chamado->chamado !!} </a></td>
  </table>

  </div>
@endisset
@isset($mensagem)
<div class="alert alert-light" role="alert">
  {{ $mensagem }}
</div>
@endisset

@stop
