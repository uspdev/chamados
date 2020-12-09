{{-- Form de deletar --}}
<form method="POST" action="setores/{{$setor->id}}" style="display: inline;">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-sm btn-light text-danger delete-item" data-toggle="tooltip" title="Remover">
        <i class="far fa-trash-alt"></i>
    </button>
</form>
