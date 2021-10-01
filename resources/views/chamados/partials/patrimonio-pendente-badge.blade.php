{{-- mostrando pendencia de patrimonio --}}
@if ($chamado->fila->config->patrimonio && $chamado->patrimonios->count() < 1)
  <a href="{{ url()->current() }}#card_patrimonios"> <span class="badge badge-danger"> Favor cadastrar um número de
      patrimônio! </span> </a>
@endif