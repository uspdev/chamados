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

@if ($chamado->fila->config->patrimonio && $chamado->patrimonios->count() < 1)
<a href="{{url()->current()}}#card_patrimonios"> <span class="badge badge-danger"> Favor cadastrar um número de patrimônio! </span> </a>
@endif
