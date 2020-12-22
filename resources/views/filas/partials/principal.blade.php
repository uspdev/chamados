<span class="text-muted">Setor:</span> {{ $fila->setor->sigla }}<br>
<span class="text-muted">Nome:</span> {{ $fila->nome }}<br>
<span class="text-muted">Descrição:</span> {{ $fila->descricao }}<br>
<br>

@include('filas.partials.config')
<br>

<br>
<span class="font-weight-bold"><i class="fab fa-wpforms"></i> Formulário</span>
<a href="{{ route('filas.createtemplate', $fila->id) }}" class="btn btn-light btn-sm text-primary"><i class="fas fa-edit"></i> Editar</a>
@can('perfilAdmin')
@include('filas.partials.template-btn-show-json-modal')        
@endcan

<div class="ml-2">
    @foreach(json_decode($fila->template) as $field=>$value)
    {{ $value->label }} - {{ $value->type }}<br>
    @endforeach
</div>
