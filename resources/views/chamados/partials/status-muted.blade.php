{{-- mostrando badge com status --}}
@if ($chamado->status == 'Triagem')
    <span class="badge badge-light text-secondary"> {{ $chamado->fila->config->triagem ? 'Triagem' : 'Novo' }} </span>
@elseif($chamado->status == 'Fechado' && $chamado->isFinalizado())
    <span class="badge badge-light text-secondary">Finalizado <i class="fas fa-lock"></i></span>
@else
    <span class="badge badge-light text-secondary"> {{ ucwords($chamado->status) }} </span>
@endif

{{-- mostrando pendencia de patrimonio --}}
@if ($chamado->fila->config->patrimonio && $chamado->patrimonios->count() < 1)
    <a href="chamados/{{$chamado->id}}#card_patrimonios"> <span class="badge badge-danger"> Cadastrar Patrimônio! </span> </a>
@endif