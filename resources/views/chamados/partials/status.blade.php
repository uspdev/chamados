@if ($chamado->status == 'Triagem')
    <span class="badge badge-{{ $color ? $color : 'secondary' }}"> {{ $chamado->fila->config->triagem ? 'Triagem' : 'Novo' }} </span>
@else
    <span class="badge badge-{{ $color ? $color : 'secondary' }}"> {{ ucwords($chamado->status) }} </span>
@endif

@if ($chamado->fila->config->patrimonio && $chamado->patrimonios->count() < 1)
    <a href="{{ url()->current() }}#card_patrimonios"> <span class="badge badge-danger"> Favor cadastrar um número de patrimônio! </span> </a>
@endif
