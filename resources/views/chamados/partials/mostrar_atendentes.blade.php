<div class="btn-group ml-3" role="group" aria-label="mostrar atendentes">

  <button class="btn btn-sm btn-{{ session('atendentes') ? 'primary' : 'outline-primary' }}"
    title="Mostrar todos os atendimentos" id="atendentes_1">
    <i class="fas fa-user-friends"></i>
  </button>

  <button class="btn btn-sm btn-{{ session('atendentes') ? 'outline-primary' : 'primary' }}"
    title="Mostrar somente meus atendimentos" id="atendentes_0">
    <i class="fas fa-user"></i>
  </button>

</div>

@section('javascripts_bottom')
  @parent
  <script>
    $(document).ready(function() {

      $('#atendentes_1').on('click', function() {
        if ($(this).hasClass('btn-outline-primary')) {
          window.location.href = 'chamados?atendentes=1'
        }
      })

      $('#atendentes_0').on('click', function() {
        if ($(this).hasClass('btn-outline-primary')) {
          window.location.href = 'chamados?atendentes=0'
        }
      })

    })
  </script>
@endsection
