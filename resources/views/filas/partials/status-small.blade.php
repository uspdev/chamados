@if ($fila->estado == 'Em produção')
<span class="text-success" data-toggle="tooltip" title="{{ $fila->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($fila->estado == 'Desativada')
<span class="text-danger" data-toggle="tooltip" title="{{ $fila->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($fila->estado == 'Em elaboração')
<span class="text-warning" data-toggle="tooltip" title="{{ $fila->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
