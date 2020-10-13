@section('styles')
@parent
<style>
  table {
    table-layout: fixed;
    word-wrap: break-word;
  }
</style>
@stop
<?php #dd($chamados); ?>
<div class="table-responsive">
  {{ $chamados->links() }}
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Id</th>
        <th>Fila</th>
        <th>Status</th>
        <th>Aberto em</th>
        <th>Chamado</th>
        <!-- <th>Prédio</th>
        <th>Sala</th> -->
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>

    @forelse ($chamados->sortByDesc('created_at') as $chamado)
      <tr>
        @include('chamados/partials/chamado')
      </tr>
    @empty
      <tr>
        <td colspan="6">Não há chamados</td>
      </tr>
    @endforelse
    
    </tbody>
  </table>
  {{ $chamados->appends(request()->query())->links() }}
</div>