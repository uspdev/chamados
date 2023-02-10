<button class="btn btn-sm btn-warning ml-3" data-toggle="collapse" data-target="#chamadosPendentes"
  title="Há chamados não finalizados de anos anteriores!">
  <div class="d-none d-sm-inline">
    {{-- vai mostrar no desktop --}}
    Pendentes
  </div>
  <span class="badge badge-pill badge-danger">{{ count($pendentes) }}</span>
</button>
