<?php
$patrimonio_detail_id = 'patrimonio-detail-' . Str::random(5); ?>

<a class="btn btn-sm btn-light text-primary py-0" data-toggle="collapse" href="#{{ $patrimonio_detail_id }}"
    role="button" aria-expanded="false" aria-controls="collapseExample">
    <i class="fas fa-info-circle"></i>
</a>

<div class="collapse" id="{{ $patrimonio_detail_id }}">
    <div class="card card-body">
        <span class="text-dark">
            <div>
                <b>Patrimônio:</b> {{ $patrimonio->numFormatado() }}
            </div>
            <div>
                <b>Responsável:</b> {{ $patrimonio->responsavel($patrimonio->replicado()->codpes) }}
            </div>
            <div>
                <b>Sala:</b> {{ $patrimonio->replicado()->codlocusp ?? '' }} -
                {{ $patrimonio->replicado()->sglcendsp ?? '' }}
            </div>
            <div>
                <b>Chamados:</b>
                <ul>
                    @foreach ($patrimonio->chamados as $chamado_pat)
                        <li class="form-inline">
                            <a href="chamados/{{$chamado_pat->id}}">
                                {{ $chamado_pat->nro }}/{{ Carbon\Carbon::parse($chamado_pat->created_at)->format('Y') }} - 
                                {{ $chamado_pat->assunto }}
                            </a>
                        </li>
                    @endforeach
                </ul>

            </div>
        </span>
    </div>
</div>
