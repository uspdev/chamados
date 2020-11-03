<button type="button" class="btn btn-sm btn-light text-primary" onclick="adicionar_pessoas({{ $chamado->id }})">
    <i class="fas fa-plus"></i> Adicionar
</button>

@include('common.adicionar-pessoas-modal', ['modal'=> $modal_pessoa])
