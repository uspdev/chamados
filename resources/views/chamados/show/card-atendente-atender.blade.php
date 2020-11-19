<form id="autotriagem" action="triagem/{{$chamado->id}}" method="post">
    @method('post')
    @csrf
    <input type="hidden" name="codpes" value="{{ \Auth::user()->codpes }}">
    <button class="btn btn-sm btn-light text-primary" type="submit" name="submit">
        <i class="fas fa-plus"></i> Atender
    </button>
</form>
