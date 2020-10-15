{{-- Form de deletar --}}
<form method="POST" action="{{url()->current()}}/{{$row->id}}" class="form-inline pull-left">
    @csrf
    @method('delete')
    <button type="submit" class="btn btn-light text-danger py-0 px-0 delete-item" data-toggle="tooltip" title="Remover">
        <i class="far fa-trash-alt"></i>
    </button>
</form>
