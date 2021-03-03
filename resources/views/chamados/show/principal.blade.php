@section('styles')
  @parent
  <style>
    #card-principal-conteudo {
      font-size: 1.1em !important;
    }

  </style>
@endsection

<div id="card-principal-conteudo">

  @if ($chamado->status == 'Fechado')
    <div class="card mb-1">
      @if ($chamado->isFinalizado())
        <div class="card-body text-white bg-danger py-2">
          Este chamado está finalizado. Caso seja necessário abra um novo chamado.
        </div>
      @else
        <div class="card-body text-dark bg-warning py-2">
          Este chamado está fechado. Caso seja necessário ele poderá ser reaberto até
          {{ $chamado->reabrirEm()->format('d/m/Y') }} adicionando um novo comentário.
        </div>
      @endif
    </div>
  @else
    @cannot('update',$chamado)
    <div class="card mb-1">
      <div class="card-body text-dark bg-warning py-2">
        Seu acesso a este chamado é somente leitura pois você não consta na lista de pessoas.
      </div>
    </div>
    @endcannot
  @endif

  <span class="text-muted">Criado por:</span>
  @if ($autor)
    {{ $autor->name }}
    ({{ $autor->setores()->wherePivot('funcao', '!=', 'Gerente')->first()->sigla ?? 'sem setor' }})
    @include('chamados.show.user-detail', ['user'=>$autor])<br>
  @else
    <span class="text-danger">** Sem autor **</span>
  @endif

  <span class="text-muted">Criado em:</span> {{ $chamado->created_at->format('d/m/Y H:i') }}<br>

  @if (!is_null($chamado->fechado_em))
    <span class="text-muted">Fechado em</span>:
    {{ $chamado->fechado_em->format('d/m/Y H:i') }}<br>
  @endif

  <span class="text-muted">Assunto:</span> {{ $chamado->assunto }}<br>
  <span class="text-muted">Descrição:</span> {{ $chamado->descricao ?? '' }}<br>
  <br>
</div>
