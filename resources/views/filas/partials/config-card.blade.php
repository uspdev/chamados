@section('styles')
  @parent
  <style>
    #card-fila-config {
      border: 1px solid coral;
      border-top: 3px solid coral;
    }

  </style>
@endsection

<div class="card mb-3" id="card-fila-config">
  <div class="card-header">
    <i class="fas fa-cogs"></i> Configurações
  </div>
  <div class="card-body">

    {{ html()->form('PUT', 'filas/' . $fila->id)->open() }}
    <input type="hidden" name="card" value="config">

    <div class="ml-2 mt-2">
      <span class="font-weight-bold">Triagem</span>
      @include('ajuda.filas.config-triagem')

      <div class="ml-2">
        <span class="text-muted mr-2">habilitar triagem:</span>

        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" type="radio" name="config[triagem]" value="1"
              {{ $fila->config->triagem ? 'checked' : '' }}>
            Sim
          </label>
        </div>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" type="radio" name="config[triagem]" value="0"
              {{ $fila->config->triagem ? '' : 'checked' }}>
            Não
          </label>
        </div>
      </div>
    </div>

    <div class="ml-2 mt-3">
      <span class="font-weight-bold">Patrimônio</span>
      @include('ajuda.filas.config-patrimonio')

      <div class="ml-2">
        <span class="text-muted mr-2">Obrigatório:</span>

        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" type="radio" name="config[patrimonio]" value="1"
              {{ $fila->config->patrimonio ? 'checked' : '' }}>
            Sim
          </label>
        </div>
        <div class="form-check form-check-inline">
          <label class="form-check-label">
            <input class="form-check-input" type="radio" name="config[patrimonio]" value="0"
              {{ $fila->config->patrimonio ? '' : 'checked' }}>
            Não
          </label>
        </div>
      </div>
    </div>

    <x-textarea class="mt-3" label="<b>Instruções</b>" name="settings[instrucoes]"
      value="{!! $fila->settings()->get('instrucoes') !!}" helpView="ajuda.filas.config-instrucoes" />

    <div class="mt-3">
      <input class="btn-sm btn-primary" id="config_submit" type="submit" name="ok" value="Salvar Configurações">
    </div>
    {{ html()->form()->close() }}
  </div>
</div>
