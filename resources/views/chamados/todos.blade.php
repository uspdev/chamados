@extends('master')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@parent

@canany(['admin', 'atendente'])

<div class="panel panel-default">

  <div class="panel-body">
    <form method="get" action="/todos">

    <div class="row">
        <div class="col-sm form-group">
          <label class="" for="">Status</label>
          <select class="form-control" id="" name="status">
            <option value="" selected>Qualquer</option>
            <option value="Triagem">Triagem</option>
            <option value="Atribuído">Atribuído</option>
            <option value="Fechado">Fechado</option>
          </select>
        </div>
        <div class="col-sm form-group">
          <label class="" for="">Prédio</label>
          <select class="form-control" id="" name="predio">
            <option value="" selected>Qualquer</option>
            @foreach($predios as $predio)
            <option value="{{$predio}}">{{$predio}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm form-group">
          <label class="" for="">Atendente</label>
          <select class="form-control" id="" name="atendente">
            <option value="" selected>Qualquer</option>
            @foreach($atendentes as $atendente)
            <option value="{{$atendente[0]}}">{{$atendente[1]}}</option>
            @endforeach
          </select>
        </div>
    </div>

        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar por número do chamado ou descrição" name="search">
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
