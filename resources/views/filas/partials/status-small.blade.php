@if ($row->estado == 'Em produção')
<span class="text-success" data-toggle="tooltip" title="{{ $row->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($row->estado == 'Desativada')
<span class="text-danger" data-toggle="tooltip" title="{{ $row->estado }}"> <i class="fas fa-circle"></i> </span>
@elseif ($row->estado == 'Em elaboração')
<span class="text-warning" data-toggle="tooltip" title="{{ $row->estado }}"> <i class="fas fa-circle"></i> </span>
@endif
