@if (Gate::check('update', $chamado) && $chamado->status != 'Fechado')
  {{-- desativando quando fechado --}}
  <label for="input_arquivo">
    <span class="btn btn-sm btn-light text-primary ml-2"> <i class="fas fa-plus"></i> Adicionar</span>
  </label>
  <span data-toggle="tooltip" data-html="true" title="Tamanho máximo de cada arquivo: {{ $max_upload_size }}KB ">
    <i class="fas fa-question-circle text-secondary ml-2"></i>
  </span>
  <form id="form_arquivo" action="arquivos" method="post" enctype="multipart/form-data" class="w-100 d-inline-block">
    @csrf
    <input type="hidden" name="chamado_id" value="{{ $chamado->id }}">
    <input type="hidden" id="max_upload_size" name="max_upload_size" value="{{ $max_upload_size }}">

    <input type="file" name="arquivo[]" id="input_arquivo"
      accept="image/jpeg,image/png,application/pdf,.dwg,.doc,.docx,.tex,.xml,.zip," class="d-none" multiple
      capture="environment">

    <div class="nome-arquivo w-100" id="nome_arquivo">
      <ul class="preview-files"></ul>
      <span id="limpar_input_arquivo" class="btn btn-outline-danger btn-sm" title="Limpar tudo"> <i
          class="far fa-trash-alt"></i></span>
      <span id="submit_form_arquivo" class="btn btn-outline-success btn-sm" title="Enviar"> <i
          class="fas fa-file-import"></i></span>
    </div>
  </form>
@endif
