<div class="btn-group ml-3" role="group" aria-label="Basic example">
  <button class="btn btn-sm btn-primary" {{ session('atendentes') ? 'disabled' : '' }} title="Mostrar todos atendentes"
    id="atendentes_1">
    <i class="fas fa-user-friends"></i>
  </button>
  <button class="btn btn-sm btn-primary" {{ session('atendentes') ? '' : 'disabled' }}
    title="Mostrar somente meus atendimentos" id="atendentes_0">
    <i class="fas fa-user"></i>
  </button>
</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('#atendentes_1').on('click', function() {
        window.location.href = 'chamados?atendentes=1'
      })

      $('#atendentes_0').on('click', function() {
        window.location.href = 'chamados?atendentes=0'
      })

    })
  </script>
@endsection
