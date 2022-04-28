@if (config('chamados.usar_replicado') == true)
  <div>
    Resp.: <b>{{ $patrimonio->responsavel() }}</b>
  </div>
  <div>
    Local: <b>{{ $patrimonio->replicado()->codlocusp ?? '' }}</b> - {{ $patrimonio->replicado()->sglcendsp ?? '' }}
  </div>
@else
  <span class="text-muted">Sem dados USP</span>
@endif
@if (count($chamadoPats = $patrimonio->chamados()->wherePivot('chamado_id', '!=', $chamado->id)->get()))
  <div>
    Outros chamados:
    <div class="ml-3">
      @foreach ($chamadoPats as $chamadoPat)
        <div>
          <a href="chamados/{{ $chamadoPat->id }}" class="d-block text-truncate">
            {{ $chamadoPat->nro }}/{{ $chamadoPat->created_at->year }}
            - {{ $chamadoPat->assunto }} | {{ strip_tags($chamadoPat->descricao) }}
          </a>
        </div>
      @endforeach
    </div>
  </div>
@endif
