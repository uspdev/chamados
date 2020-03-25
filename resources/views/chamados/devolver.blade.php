@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('content')
@parent

    <div class="card bg-light mb-3">

      <div class="card-header">
        @if(config('chamados.usar_replicado') == 'true')
            {{ \Uspdev\Replicado\Pessoa::dump($chamado->user->codpes)['nompes'] }}]
        @endif
        {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}</div>
      <div class="card-body">
        @include('chamados/partials/chamado')

        <p class="card-text">{!! $chamado->chamado !!}</p>
      </div>
    </div>

<form method="POST" role="form" action="{{ route('chamados.update', $chamado) }}">
      @csrf
      {{ method_field('patch') }}
      <div class="form-group">
        <button type="submit" class="btn btn-primary" name="status" value="devolver">Devolver para triagem</button>
      </div>

  </form>

@stop

