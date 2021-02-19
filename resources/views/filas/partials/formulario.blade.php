<span class="font-weight-bold"><i class="fab fa-wpforms"></i> Formulário</span>
<a href="{{ route('filas.createtemplate', $fila->id) }}" class="btn btn-light btn-sm text-primary"><i class="fas fa-edit"></i> Editar</a>

@includewhen('perfiladmin', 'filas.partials.template-btn-show-json-modal')

<div class="ml-2">
    <strong>Label - Tipo - Visibilidade</strong>
    <br>
    @foreach(json_decode($fila->template) as $field=>$value)
    {{ $value->label }} - {{ $value->type }} - {{ $value->can ?? 'todos' }}<br>
    @endforeach
</div>
<br>