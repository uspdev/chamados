<form action="arquivos/{{ $arquivo->id }}" method="post">
  @csrf
  @method('delete')
  <button type="submit"
    onclick="return confirm(&#39;Tem certeza que deseja deletar {{ $arquivo->nome_original }}?&#39;);"
    class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
</form>
