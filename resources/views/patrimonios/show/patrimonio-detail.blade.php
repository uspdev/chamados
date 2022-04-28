@if (config('chamados.usar_replicado') == 'true')
<div>
  Responsável: <b>{{ $patrimonio->responsavel() }}</b>
</div>
<div>
  Sala:
  <b>{{ $patrimonio->replicado()->codlocusp ?? '' }} -
  {{ $patrimonio->replicado()->sglcendsp ?? '' }}
</b>
</div>
@endif
<div>
Outros chamados:
<div class="ml-3">
  @foreach ($patrimonio->chamados as $chamado_pat)
    @if ($chamado->id != $chamado_pat->id)
      <div>
        <a href="chamados/{{ $chamado_pat->id }}" class="d-block text-truncate">
          {{ $chamado_pat->nro }}/{{ $chamado_pat->created_at->year }}
          - {{ $chamado_pat->assunto }} | {{ strip_tags($chamado_pat->descricao) }}
        </a>
      </div>
    @elseif($patrimonio->chamados->count() == 1)
      Não existem outros chamados.
    @endif
  @endforeach
</div>
</div>