{{-- mostrando badge com status --}}
@if ($chamado->status == 'Triagem')
  <span class="badge badge-warning"> {{ $chamado->fila->config->triagem ? 'Triagem' : 'Novo' }} </span>
@elseif ($chamado->status == 'Em Andamento')
  <span class="badge badge-success"> {{ ucwords($chamado->status) }} </span>
@elseif ($chamado->status == 'Fechado')
  <span class="badge badge-dark">
    {!! $chamado->isFinalizado() ? 'Finalizado &nbsp;<i class="fas fa-lock"></i>' : 'Fechado' !!}
  </span>
@else
  <span class="badge badge-{{ $chamado->retornarCor() }}"> {{ ucwords($chamado->status) }} </span>
@endif
