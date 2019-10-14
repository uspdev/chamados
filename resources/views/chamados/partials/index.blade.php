<div class="table-responsive">
  {{ $chamados->links() }}
  <table class="table table-striped">
    <thead>
      <tr>
        <th class="col-sm-6">Detalhes</th>
        <th class="col-sm-6">Chamado</th>
      </tr>
    </thead>

    <tbody>

@forelse ($chamados->sortByDesc('created_at') as $chamado)
      <tr>
       <td class="col-sm-6">
        @include('chamados/partials/chamado')
      </td class="col-sm-6">
        <td>{!! $chamado->chamado !!}</td>
      </tr>
@empty
    <tr>
        <td colspan="6">Não há chamados</td>
    </tr>
@endforelse
</tbody>
</table>

{{ $chamados->links() }}
</div>
