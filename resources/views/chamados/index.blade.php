@extends('master')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@parent

@canany(['admin', 'atendente'])
<div class="panel panel-default">
  <div class="panel-heading"><b>Filtrar por:</b></div>
  <div class="panel-body">
    <form method="get" action="/chamados">
        <div>
          <label class="" for="">status</label>
          <select class="" id="" name="status">
            <option selected>Qualquer</option>
            <option value="1">Triagem</option>
            <option value="1">Atribuído</option>
            <option value="2">Fechado</option>
          </select>
        </div>
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar por número do chamado, descrição ou comentário" name="search">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-success"> Buscar </button>
            </span>
        </div><!-- /input-group -->
    </form>

  </div>
</div>
<br>
@endcanany

@include('chamados/partials/index')

@stop
