@if ($chamado->status == 'Fechado')
<span class="badge badge-danger"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Aguardando Peças')
<span class="badge badge-dark"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Aguardando Solicitante')
<span class="badge badge-info"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Triagem')
<span class="badge badge-warning"> {{ $chamado->fila->config->triagem ? 'Triagem': 'Novo' }} </span>
@else
{{-- atribuido é default --}}
<span class="badge badge-success"> {{ $chamado->status }} </span>
@endif
