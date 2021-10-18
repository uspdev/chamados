@section('styles')
@parent
<style>
    #card-fila-formulario {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }

</style>
@endsection

<div class="card mb-3" id="card-fila-formulario">
    <div class="card-header">
        <i class="fab fa-wpforms"></i> Formulário
        <span class="small">@include('ajuda.filas.formulario')</span>

        <a href="{{ route('filas.createtemplate', $fila->id) }}" class="btn btn-light btn-sm text-primary">
            <i class="fas fa-edit"></i> Editar
        </a>
        @includewhen(Gate::check('perfiladmin'), 'filas.partials.template-btn-show-json-modal')
    </div>
    <div class="card-body">
        <div class="ml-2">
            <strong>Label - Tipo - Visibilidade</strong>
            <br>
            @foreach(json_decode($fila->template) as $field=>$value)
            {{ $value->label }} - {{ $value->type }} - {{ $value->can ?? 'todos' }}<br>
            @endforeach
        </div>
    </div>
</div>
