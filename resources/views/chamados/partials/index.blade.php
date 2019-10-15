@section('styles')
@parent
<style>
    table {
        table-layout: fixed;
        word-wrap: break-word;
    }
</style>
@stop

<div class="table-responsive">
  {{ $chamados->links() }}
  <table class="table table-striped">
    <thead>
      <tr>
        <th style="width: 50%">Detalhes</th>
        <th style="width: 50%">Chamado</th>
      </tr>
    </thead>

    <tbody>

@forelse ($chamados->sortByDesc('created_at') as $chamado)
      <tr>
       <td>
        @include('chamados/partials/chamado')
      </td>
      <td> <a href="/chamados/{{$chamado->id}}"> {!! $chamado->chamado !!} </a></td>
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
