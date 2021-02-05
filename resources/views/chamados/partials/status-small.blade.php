@if ($chamado->status == 'Triagem')
    <span class="text-warning" data-toggle="tooltip" title="{{ $chamado->fila->config->triagem ? 'Triagem' : 'Novo' }}"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Em Andamento')
    <span class="text-success" data-toggle="tooltip" title="{{ ucwords($chamado->status) }}"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Fechado')
    <span class="text-dark" data-toggle="tooltip" title="{{ ucwords($chamado->status) }}"> <i class="fas fa-circle"></i> </span>
@else
    <span class="text-{{ $color ? $color : 'secondary' }}" data-toggle="tooltip" title="{{ ucwords($chamado->status) }}"> <i class="fas fa-circle"></i> </span>
@endif