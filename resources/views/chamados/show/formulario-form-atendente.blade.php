<div class="ml-2">
  <div class="form-group">
    <form id="form_atendente" name="form_atendente" method="POST" action="chamados/{{ $chamado->id }}">
      @csrf
      @method('PUT')
      @foreach ($formAtendente as $input)
        @foreach ($input as $element)
          {{ $element }}
        @endforeach
        <br>
      @endforeach
      {{ html()->submit('OK', ['id' => 'btn_salvar_form_atendente', 'class' => 'btn btn-primary d-none']) }}
    </form>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {

      $('#form_atendente').find(':input').on('change', function() {
        $('#btn_salvar_form_atendente').removeClass('d-none')
      })

    })
  </script>
@stop
