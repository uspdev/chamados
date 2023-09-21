@extends('layouts.app')

@section('content')
  @parent

  {{ $patrimonio->numpat }}<br>

  Resp.: {{ $patrimonio->responsavel() }}<br>
  {{ $patrimonio->marcaModeloTipo() }}<br>

  @foreach ($patrimonio->chamados as $c)
    {{ $c->nro }}/{{ $c->created_at->format('Y') }} |

    @if ($c->fechado_em)
      fechado em {{ $c->fechado_em->format('d/m/Y') }}
    @else
      aberto
    @endif

    | <a href="{{ route('chamados.show', $c->id) }}">{{ $c->assunto }}</a><br>
  @endforeach
  <br>
  <div>
    <pre>{{ json_encode($patrimonio->replicado(), JSON_PRETTY_PRINT) }}</pre>
  </div>
@endsection
