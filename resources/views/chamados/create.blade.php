@extends('master')

@section('title', 'Novo Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
  @parent
@stop

@section('content')
  @parent

  <div class="card bg-light mb-3">
    <div class="card-header h5">
      <span class="text-muted">Novo chamado para</span> ({{ $fila->setor->sigla }}) {{ $fila->nome }}
      @include('chamados.partials.instrucoes-da-fila-badge')

    </div>
    <div class="card-body">

      @include('chamados.partials.instrucoes-da-fila')

      <form method="POST" role="form" action="{{ route('chamados.store', $fila->id) }}">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="autor" class="control-label">Por</label>
              <input class="form-control" type="text" name="autor" value="{{ \Auth::user()->name }}" disabled>
              <br>
              <label for="assunto" class="control-label">Assunto</label>
              <input class="form-control" type="text" name="assunto" value="{{ old('assunto') }}">
              <br>
              <label for="descricao" class="control-label">Descrição</label>
              <textarea class="form-control" name="descricao">{{ old('descricao') }}</textarea>
            </div>
          </div>

          {{-- renderiza o formulario personalizado da fila --}}
          <div class="col-md-6">
            <div class="form-group">
              @foreach ($form as $input)
                @foreach ($input as $element)
                  {{ $element }}
                @endforeach
                <br>
              @endforeach
            </div>
          </div>

        </div>
        <div class="form-group card-header">
          <button type="button" class="btn btn-secondary" onclick="window.history.back();"><i class="fas fa-times-circle"></i> Cancelar</button>
          <button type="submit" class="btn btn-primary">Enviar <i class="fas fa-check-circle"></i></button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('javascripts_bottom')
    @parent
    {{ Html::script('js/functions.js') }}
    <script type="text/javascript">
      /* @autor uspdev/alecosta 10/02/2022
       * Ao carregar a página ordena todos os campos caixa de seleção adicionados na fila
      */
      $(document).ready(function() {
        // Pega todos os campos extras que são caixa de seleção
        $('select[name^="extras"]').each(function () {
          var nameField = $(this).prop('name');
          // Considerando que todo campo adicionado na fila tenha o nome padrão extras[alguma-coisa]
          var start = 7;
          var end = nameField.length - 1;
          // Ordena as opções do campo caixa de seleção
          $(ordenarOpcoes(nameField.substring(start, end)));
        });
      });
  </script>
@endsection
