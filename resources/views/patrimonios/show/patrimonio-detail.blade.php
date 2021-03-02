<a class="btn btn-sm btn-light text-primary py-0" data-toggle="collapse" href="#patrimonio_{{ $patrimonio->numpat }}" role="button" aria-expanded="false" aria-controls="collapseExample">
    <i class="fas fa-info-circle"></i>
</a>

<div class="collapse" id="patrimonio_{{ $patrimonio->numpat }}">
    <div class="card card-body">
        <span class="text-dark">
            <div>
                <b>Patrimônio:</b> {{ $patrimonio->numFormatado() }}
            </div>
            @if (config('chamados.usar_replicado') == 'true')
            <div>
                <b>Responsável:</b> {{ $patrimonio->responsavel($patrimonio->replicado()->codpes) }}
            </div>
            <div>
                <b>Sala:</b> {{ $patrimonio->replicado()->codlocusp ?? '' }} - {{ $patrimonio->replicado()->sglcendsp ?? '' }}
            </div>
            @endif
            <div>
                <b>Chamados:</b>
                <div class="ml-3">
                    @foreach ($patrimonio->chamados as $chamado_pat)
                    @if ($chamado->id != $chamado_pat->id)
                    <div>
                        <a href="chamados/{{ $chamado_pat->id }}">
                            {{ $chamado_pat->nro }}/{{ $chamado_pat->created_at->year }}
                            - {{ $chamado_pat->assunto }}
                        </a>
                    </div>
                    @elseif($patrimonio->chamados->count() == 1)
                    Não existem outros chamados com esse patrimônio.
                    @endif
                    @endforeach
                </div>
            </div>
        </span>
    </div>
</div>
