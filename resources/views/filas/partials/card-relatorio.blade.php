@section('styles')
  @parent
  <style>
    #card-fila-relatorios {
      border: 1px solid blue;
      border-top: 3px solid blue;
    }
  </style>
@endsection

<div class="card mb-3" id="card-fila-relatorios">
  <div class="card-header">
    <i class="fas fa-chart-line"></i> Relatórios
  </div>
  <div class="card-body">
    <ul class="list-unstyled">
      <li>Contagem (últimos 5 anos)
          @include('filas.partials.chamados-por-ano')
      </li>
    </ul>
  </div>
</div>
