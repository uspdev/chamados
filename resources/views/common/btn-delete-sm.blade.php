{{-- Form de deletar --}}
<form method="POST" action="{{ $action }}" class="form-inline pull-left">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-light text-danger px-2 py-0 delete-item" data-toggle="tooltip" title="Remover">
        <i class="far fa-trash-alt"></i>
    </button>
</form>
