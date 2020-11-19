<span class="text-muted">Setor:</span> {{ $data->row['setor']->sigla }}<br>
<span class="text-muted">Nome:</span> {{ $data->row['nome'] }}<br>
<span class="text-muted">Descrição:</span> {{ $data->row['descricao'] }}<br>
<br>

@include('filas.partials.triagem')

<br>
<span class="font-weight-bold"><i class="far fa-eye"></i> Visibilidade</span>

<div class="ml-2">
    <span class="text-muted">por usuários:</span>
    <input type="checkbox" name="publico" value="todos" checked> Todos &nbsp;
    <input type="checkbox" name="publico" value="todos" checked> Alunos GR &nbsp;
    <input type="checkbox" name="publico" value="todos" checked> Alunos PG/PD &nbsp;
    <input type="checkbox" name="publico" value="todos" checked> Servidores &nbsp;
    <input type="checkbox" name="publico" value="todos" checked> Docentes &nbsp;
    <br>
    <span class="text-muted">por setor:</span> Todos os setores<br>
</div>

<br>
<span class="font-weight-bold"><i class="fab fa-wpforms"></i> Formulário</span>
<a href="{{ route('filas.createtemplate', $data->row->id) }}" class="btn btn-light btn-sm text-primary"><i class="fas fa-edit"></i> Editar</a>
@can('perfilAdmin')
@include('filas.partials.template-btn-show-json-modal')        
@endcan

<div class="ml-2">
    @if(!empty($data->row->template))
    @foreach(json_decode($data->row->template) as $field=>$value)
    {{ $value->label }}<br>
    @endforeach
    @endif
    Descrição (padrão)<br>
</div>
