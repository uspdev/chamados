<h1>Chamados de {{ $site->dominio.config('sites.dnszone') }}</h1>

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Infos</th>
        <th>Chamado</th>
      </tr>
    </thead>

    <tbody>

@forelse ($site->chamados->sortByDesc('created_at') as $chamado)
      <tr>
       <td>
       <ul style="list-style-type: none;">
        <li><b>id: </b>{{ $chamado->id }}</li>
        <li><b>por: </b>{{ $chamado->user->name }}</li>
        <li><b>em: </b>{{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}</li>
        <li><b>status: </b>{{ $chamado->status }}</li>
        <li><b>tipo: </b>{{ $chamado->tipo }}</li>
       </li>
      </td>
        <td><a href="/chamados/{{$chamado->site_id}}/{{$chamado->id}}">{!! $chamado->descricao !!}</a></td>
      </tr>
@empty
    <tr>
        <td colspan="6">Não há chamados para esse site</td>
    </tr>
@endforelse
</tbody>
</table>

</div>

