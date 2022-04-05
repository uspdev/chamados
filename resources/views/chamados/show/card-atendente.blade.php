<a name="card_atendente"></a>
<div class="card bg-light border-warning mb-3" id="card-atendente">
  <div class="card-header bg-warning">
    <div class="form-inline">
      Atendente
      <span title="Esta região é visível somente para o atendente, com conteúdo e ações próprios." class="ajuda mx-2"
        data-toggle="tooltip"><i class="fas fa-question-circle"></i></span>

      @if (Gate::check('filas.update', $chamado->fila))
        @includeWhen($chamado->status != 'Fechado', 'chamados.partials.show-triagem-modal')
      @endif
      @if ($chamado->fila->config->triagem == false && $chamado->status == 'Triagem')
        @include('chamados.partials.show-atender-modal')
      @endif

    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-4">
        @includewhen($chamado->status != 'Fechado', 'chamados.show.mudar-status')
      </div>
      <div class="col-md-4">
        @includewhen($chamado->status == 'Fechado', 'chamados.show.formulario-dados-atendente')
        @includewhen($chamado->status != 'Fechado', 'chamados.show.formulario-form-atendente')
      </div>
      <div class="col-md-4">
        @include('chamados.show.anotacoes-tecnicas')
      </div>

    </div>
  </div>
</div>
