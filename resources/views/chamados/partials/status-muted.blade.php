@if ($chamado->status == 'Fechado')
<span class="badge badge-light text-secondary"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Aguardando Peças')
<span class="badge badge-light text-secondary"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Aguardando Solicitante')
<span class="badge badge-light text-secondary"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Triagem')
<span class="badge badge-light text-secondary"> {{ $chamado->fila->config->triagem ? 'Triagem': 'Novo' }} </span>
@else
{{-- atribuido é default --}}
<span class="badge badge-light text-secondary"> {{ $chamado->status }} </span>
@endif

@if ($chamado->fila->config->patrimonio && $chamado->patrimonios->count() < 1)
<a href="chamados/{{$chamado->id}}#card_patrimonios"> <span class="badge badge-danger"> Cadastrar Patrimônio! </span> </a>
@endif