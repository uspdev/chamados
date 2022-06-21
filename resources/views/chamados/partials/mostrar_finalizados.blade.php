<div class="btn-group ml-3" role="group" aria-label="Basic example">

  <button class="btn btn-sm btn-{{ session('finalizado') ? 'dark' : 'outline-dark' }}"
    title="Incluir finalizados (fechados há mais de 10 dias)" id="finalizado_1">
    <i class="fas fa-lock"></i>
  </button>

  <button class="btn btn-sm btn-{{ session('finalizado') ? 'outline-dark' : 'dark' }}" title="Mostrar recentes"
    id="finalizado_0">
    <i class="fas fa-circle"></i>
  </button>

</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('#finalizado_1').on('click', function() {
        if ($(this).hasClass('btn-outline-dark')) {
          window.location.href = 'chamados?finalizado=1'
        }
      })

      $('#finalizado_0').on('click', function() {
        if ($(this).hasClass('btn-outline-dark')) {
          window.location.href = 'chamados?finalizado=0'
        }
      })

    })
  </script>
@endsection
