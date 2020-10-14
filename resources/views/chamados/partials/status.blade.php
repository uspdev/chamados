@if ($chamado->status == 'Atribuído')
<span class="badge badge-success"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Fechado')
<span class="badge badge-secondary"> {{ $chamado->status }} </span>
@elseif ($chamado->status == 'Triagem')
<span class="badge badge-danger"> {{ $chamado->status }} </span>
@else
@endif
