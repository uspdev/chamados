@if ($chamado->status == 'Triagem')
    <span class="text-warning"> <i class="fas fa-circle"></i> </span>
    <span class="d-none">triagem</span>
@elseif ($chamado->status == 'Em Andamento')
    <span class="text-success"> <i class="fas fa-circle"></i> </span>
    <span class="d-none">em andamento</span>
@elseif ($chamado->status == 'Fechado')
    <span class="text-dark"> <i class="fas fa-circle"></i> </span>
    <span class="d-none">fechado</span>
@else
    <span class="text-{{ $color ? $color : 'secondary' }}"> <i class="fas fa-circle"></i> </span>
    <span class="d-none">em andamento</span>
@endif