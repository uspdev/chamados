@section('styles')
@parent
<style>
    #card-fila-principal {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }

</style>
@endsection

<div class="card mb-3" id="card-fila-principal">
    <div class="card-header">
        Informações básicas
        @if($fila->estado != 'Desativada')
        &nbsp; | &nbsp;
        @includewhen($fila->estado != 'Desativada', 'common.list-table-btn-edit', ['row'=>$fila])
        @endif
    </div>
    <div class="card-body">
        <span class="text-muted">Setor:</span> {{ $fila->setor->sigla }}<br>
        <span class="text-muted">Nome:</span> {{ $fila->nome }}<br>
        <span class="text-muted">Descrição:</span> {{ $fila->descricao }}<br>
    </div>
</div>
