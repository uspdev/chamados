@if ($chamado->status == 'Fechado')
<span class="badge badge-secondary"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Triagem')
<span class="badge badge-warning"> {{ $chamado->fila->config->triagem ? 'Triagem': 'Novo' }} </span>
@else
{{-- atrbuido é default --}}
<span class="badge badge-success"> {{ $chamado->status }} </span>
@endif
