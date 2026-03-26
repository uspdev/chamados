@if (Gate::check('update', $chamado))
  <div class="arquivo-acoes">
    <form action="arquivos/{{ $arquivo->id }}" method="post" class="d-inline-block">
      @csrf
      @method('delete')
      <button type="submit" onclick="return confirm('Tem certeza que deseja deletar {{ $arquivo->nome_original }}?');"
        class="btn btn-outline-danger btn-sm btn-deletar btn-arquivo-acao"><i class="far fa-trash-alt"></i></button>
    </form>
    <form class="d-inline-block">
      <button type="button" class="btn btn-outline-warning btn-sm btn-editar btn-arquivo-acao"><i
          class="far fa-edit"></i></button>
    </form>
  </div>

  <form action="arquivos/{{ $arquivo->id }}" method="post" class="editar-nome-arquivo-form">
    @csrf
    @method('patch')
    <div class="input-wrapper">
      <input type="text" name="nome_arquivo" class="input-nome-arquivo"
        value="{{ pathinfo($arquivo->nome_original, PATHINFO_FILENAME) }}">
    </div>
    <div class="btns-wrapper">
      <button type="submit" class="btn btn-outline-success btn-sm ml-2 btn-arquivo-acao"><i
          class="fas fa-check"></i></button>
      <button type="button" class="btn btn-outline-danger btn-sm  btn-arquivo-acao limpar-edicao-nome"><i
          class="fas fa-times"></i></button>
    </div>
  </form>
@endif
