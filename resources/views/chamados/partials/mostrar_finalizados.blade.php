<div class="form-group form-check">
  <div class="custom-control custom-switch">
    <input type="checkbox" class="custom-control-input" id="mostrar_finalizados"
      {{ session('finalizado') ? 'checked' : '' }}>
    <label class="custom-control-label" for="mostrar_finalizados">
      Mostrar fechados há mais de 10 dias (finalizados)
    </label>
  </div>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('#mostrar_finalizados').change(function() {
        if (this.checked) {
          window.location.href = 'chamados?finalizado=1'
        } else {
          window.location.href = 'chamados?finalizado=0'
        }
      })

    })
  </script>
@endsection
