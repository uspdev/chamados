@section('styles')
  @parent
  <style>
    #card-fila-estados {
      border: 1px solid chocolate;
      border-top: 3px solid chocolate;
    }

  </style>
@endsection

<div class="card mb-3" id="card-fila-estados">
  <div class="card-header">
    Estados
    @include('ajuda.filas.config-status-btn')

    <button type="button" class="btn btn-sm btn-light text-primary" id="btn_add_status">
      <i class="fas fa-plus"></i> Adicionar
    </button>
  </div>
  <div class="card-body">
      
    @include('ajuda.filas.config-status')

    {!! Form::open(['url' => 'filas/' . $fila->id, 'name' => 'form_config']) !!}
    @method('put')
    <input type="hidden" name="card" value="estados">

    <div class="form-row">
      <div class="col-12 form-group ml-2">
        @foreach ($fila->config->status as $input)
          <div class="form-inline mb-2">
            <input class="form-control col-5" type="text" name="config[status][select][]" maxlength="30"
              value="{{ $input->label ?? '' }}">

            <select name="config[status][select_cor][]" class="form-control col-3 ml-2">
              <option value="">Selecione...</option>
              <option value="danger" class="bg-danger text-dark" @if ($input->color == 'danger') selected @endif>Danger</option>
              <option value="warning" class="bg-warning text-dark" @if ($input->color == 'warning') selected @endif>Warning</option>
              <option value="primary" class="bg-primary text-white" @if ($input->color == 'primary') selected @endif>Primary</option>
              <option value="secondary" class="bg-secondary text-white" @if ($input->color == 'secondary') selected @endif>Secondary</option>
              <option value="success" class="bg-success text-white" @if ($input->color == 'success') selected @endif>Success</option>
              <option value="info" class="bg-info text-white" @if ($input->color == 'info') selected @endif>Info</option>
              <option value="dark" class="bg-dark text-white" @if ($input->color == 'dark') selected @endif>Dark</option>
              <option value="white" class="bg-white text-dark" @if ($input->color == 'white') selected @endif>White</option>
            </select>

            <div style="flex: 0 0 150px;">
              <span class="ml-2 badge badge-{{ $input->color ?? '' }}">Teste</span>
              <span class="ml-2 circulo text-{{ $input->color ?? '' }}"><i class="fas fa-circle"></i></span>
              <button type="button" class="btn btn-sm btn-danger btn_del_status ml-2" data-toggle="tooltip"
                title="Remover">
                <i class="fa fa-minus"></i>
              </button>
            </div>

          </div>
        @endforeach
      </div>
    </div>

    <div class="mt-3">
      <input class="btn-sm btn-primary" id="config_submit" type="submit" name="ok" value="Salvar Configurações">
    </div>
    {!! Form::close() !!}
  </div>
</div>

{{-- usado no add estado, iagual ao estado já existente --}}
<template id="form-inline">
  <div class="form-inline mb-2">
    <input class="form-control col-5" type="text" name="config[status][select][]" value="" maxlength="30">
    <select name="config[status][select_cor][]" class="form-control col-3 ml-2">
      <option value="">Selecione...</option>
      <option value="danger" class="bg-danger text-dark">Danger</option>
      <option value="warning" class="bg-warning text-dark">Warning</option>
      <option value="primary" class="bg-primary text-white">Primary</option>
      <option value="secondary" class="bg-secondary text-white">Secondary</option>
      <option value="success" class="bg-success text-white">Success</option>
      <option value="info" class="bg-info text-white">Info</option>
      <option value="dark" class="bg-dark text-white">Dark</option>
      <option value="white" class="bg-white text-dark">White</option>
    </select>
    <div style="flex: 0 0 150px;">
      <span class="ml-2 badge">Teste</span>
      <span class="ml-2 circulo"><i class="fas fa-circle"></i></span>
      <button type="button" class="btn btn-sm btn-danger btn_del_status ml-2" data-toggle="tooltip" title="Remover">
        <i class="fa fa-minus"></i>
      </button>
    </div>
  </div>

</template>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      var card_estado = $('#card-fila-estados')

      card_estado.on('click', '#btn_add_status', function() {
        card_estado.find('.form-group').append($('#form-inline').html())
      });

      card_estado.on('click', '.btn_del_status', function() {
        if (confirm("Tem certeza que quer apagar?")) {
          var form = $(this).closest('.form-inline')
          form.remove();
        }
      });

      // ao mudar no select vamos mudar as cores em exibição
      card_estado.on('change', 'select', function(e) {
        console.log('select change to ', this.value);
        var form = $(this).closest('.form-inline')
        form.find('.badge').removeClass().addClass('ml-2 badge badge-' + this.value)
        form.find('.circulo').removeClass().addClass('ml-2 circulo text-' + this.value)
      });
    })
  </script>
@endsection
