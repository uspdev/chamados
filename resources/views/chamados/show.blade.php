@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
@parent
<script>CKEDITOR.replace( 'comentario' );</script>
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

@forelse ($chamado->comentarios->sortBy('created_at') as $comentario)

    <div class="card bg-light mb-3">
      <div class="card-header">{{ $comentario->user->name }} - {{ Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i') }}</div>
      <div class="card-body">
        <p class="card-text">{!! $comentario->comentario !!}</p>
      </div>
    </div>

@empty
    Não há comentários
@endforelse

@can('update',$chamado)
  <form method="POST" role="form" action="comentarios/{{$chamado->id}}">
      @csrf

      <div class="form-group">
        <label for="comentario"><b>Novo comentário:</b></label>
        <textarea class="form-control" id="comentario" name="comentario" rows="7"></textarea>
      </div>

      @if($chamado->status == 'Triagem' or $chamado->status == 'Atribuído')
      <div class="form-group">
        <button type="submit" class="btn btn-primary" value="">Enviar</button>
      </div>

        <div class="form-group">
          <button type="submit" class="btn btn-danger" name="status" value="Fechado">Enviar e fechar chamado</button>
      </div>
      @else
        <div class="form-group">
          <button type="submit" class="btn btn-danger" name="status" value="Triagem">Enviar e reabrir chamado</button>
      </div>
      @endif
  </form>
@endcan

@stop

