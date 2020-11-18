@if ($chamado->status == 'Atribuído')
<span class="text-success" data-toggle="tooltip" title="{{ $chamado->status }}"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Fechado')
<span class="text-secondary" data-toggle="tooltip" title="{{ $chamado->status }}"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Triagem')
<span class="text-warning" data-toggle="tooltip" title="{{ $chamado->fila->config->triagem ? 'Triagem': 'Novo' }}"> <i class="fas fa-circle"></i> </span>
@else
@endif
