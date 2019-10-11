<div class="table-responsive">
  {{ $chamados->links() }}
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Detalhes</th>
        <th>Chamado</th>
      </tr>
    </thead>

    <tbody>

@forelse ($chamados->sortByDesc('created_at') as $chamado)
      <tr>
       <td>
       <ul style="list-style-type: none;">
        <li><b>id: </b>#{{ $chamado->id }}</li>
        <li><b>total de comentários</b>: {{ $chamado->comentarios->count() }}</li>
        <li><b>por: </b>{{ \Uspdev\Replicado\Pessoa::dump($chamado->user->codpes)['nompes'] }}</li>
        <li><b>em: </b>{{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}</li>
        <li><b>status: </b>{{ $chamado->status }}</li>
        @if($chamado->status == 'Atribuído')
            <li><b>triagem por</b>: {{ \Uspdev\Replicado\Pessoa::dump($chamado->triagem_por)['nompes'] }}</li>
            <li><b>atribuído para</b>: {{ \Uspdev\Replicado\Pessoa::dump($chamado->atribuido_para)['nompes'] }}</li>
        @endif
        <li><b>prédio</b>: {{ $chamado->predio }}</li>
        <li><b>sala</b>: {{ $chamado->sala }}</li>
        
        <li><b>Categoria: </b>{{ $chamado->categoria->nome }}</li>
       </li>

        </br>
        @if($chamado->status == 'Triagem')
            @can('admin')
            <a href="/chamados/{{$chamado->id}}/edit" class="btn btn-success">Atribuir</a>
            @endcan
        @endif
      </td>
        <td><a href="/chamados/{{$chamado->id}}">{!! $chamado->chamado !!}</a></td>
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
