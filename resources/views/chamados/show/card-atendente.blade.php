<a name="card_atendente"></a>
<div class="card bg-light border-warning mb-3" id="card-atendente">
  <div class="card-header bg-warning">
    <div class="form-inline">
      Atendente<span class="ml-3"></span>

      @if (Gate::check('filas.update', $chamado->fila))
        @includeWhen($chamado->status != 'Fechado', 'chamados.partials.show-triagem-modal')
      @endif
      @if ($chamado->fila->config->triagem == false && $chamado->status == 'Triagem')
        @include('chamados.partials.show-atender-modal')
      @endif
      <span class="mx-5 small">
        @includewhen($chamado->status != 'Fechado', 'chamados.show.mudar-status')
      </span>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-primary alert-dismissible show" role="alert">
          Ações e anotações do atendente. Caso seja relevante para o solicitante, utilize o card <b>Comentários</b>.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>

      {{-- potencialmente pode haver dados-atendente orfaos se em algum momento havia 
        um campo no form, foi preenchido e depois o campo foi eemovido da fila #319 --}}
      <div class="{{ $formAtendente ? 'col-md-6' : 'col-md-12' }}">
        @if ($formAtendente)
          @includewhen($chamado->status == 'Fechado', 'chamados.show.formulario-dados-atendente')
          @includewhen($chamado->status != 'Fechado', 'chamados.show.formulario-form-atendente')
          {!! '</div><div class="col-md-6">' !!}
        @endif
        @include('chamados.show.anotacoes-tecnicas')
      </div>

    </div>
  </div>
</div>
