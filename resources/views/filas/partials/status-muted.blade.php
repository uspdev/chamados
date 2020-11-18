@if ($row->estado == 'Desativada')
<span class="badge badge-light text-secondary"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Em elaboração')
<span class="badge badge-light text-secondary"> {{ $row->estado }} </span>
@elseif ($row->estado == 'Em produção')
<span class="badge badge-light text-secondary"> {{ $row->estado }} </span>
@endif
