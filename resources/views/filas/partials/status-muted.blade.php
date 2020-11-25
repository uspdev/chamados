@if ($fila->estado == 'Desativada')
<span class="badge badge-light text-secondary"> {{ $fila->estado }} </span>
@elseif ($fila->estado == 'Em elaboração')
<span class="badge badge-light text-secondary"> {{ $fila->estado }} </span>
@elseif ($fila->estado == 'Em produção')
<span class="badge badge-light text-secondary"> {{ $fila->estado }} </span>
@endif
