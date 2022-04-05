@section('styles')
  @parent
  <style>
    #btn_salvar_status {
      display: none;
    }

  </style>
@endsection

<form id="status_form" name="status_form" method="POST" action="chamados/{{ $chamado->id }}" class="">
  @csrf
  @method('PUT')
  <div class="form-group form-inline ml-2">
    <b>{{ Form::label('status', 'Status') }}</b>
    {{ Form::select('status', $status_list, $chamado->status, ['id' => 'estado','class' => 'form-control mx-2','placeholder' => 'Escolha um..']) }}
    {{ Form::submit('OK', ['id' => 'btn_salvar_status', 'class' => 'btn btn-primary mx-2']) }}
  </div>
</form>

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {

      var $estado = $('#status_form').find('#estado')
      $estado.change(function() {
        $('#status_form').find('#btn_salvar_status').show()
      })

    })
  </script>
@stop
