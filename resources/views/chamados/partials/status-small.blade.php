@if ($chamado->status == 'Triagem')
    <span class="text-warning"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Em Andamento')
    <span class="text-success"> <i class="fas fa-circle"></i> </span>
@elseif ($chamado->status == 'Fechado')
    <span class="text-dark"> <i class="fas fa-circle"></i> </span>
@else
    <span class="text-{{ $color ? $color : 'secondary' }}"> <i class="fas fa-circle"></i> </span>
@endif