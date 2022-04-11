<form method="post" action="chamados/{{ $chamado->id }}/triagem">
  @csrf
  <input type="hidden" name="codpes" value="{{ \Auth::user()->codpes }}">

  <button type="submit" class="btn btn-sm btn-light text-success ml-3">
    <i class="fas fa-check"></i> Atender
  </button>

</form>
