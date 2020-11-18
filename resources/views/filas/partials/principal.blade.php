<span class="text-muted">Setor:</span> {{ $data->row['setor']->sigla }}<br>
<span class="text-muted">Nome:</span> {{ $data->row['nome'] }}<br>
<span class="text-muted">Descrição:</span> {{ $data->row['descricao'] }}<br>
<br>
<span class="font-weight-bold">Habilitar triagem </span>
<span data-toggle="tooltip" data-html="true" title="O gerente da fila fará a distribuição dos chamados entre os atendentes/Os atendentes farão auto atribuições por conta própria.">
    <i class="fas fa-question-circle text-primary"></i>
</span><br>



<div class="ml-2">

{!! Form::open(['url'=>'filas/'.$fila->id, 'name' => 'form_config']) !!}
@method('put')
@csrf
<div class="btn-group">
    <button type="submit" class="btn btn-sm {{($fila->config->triagem) ? 'btn-success' : 'btn-secondary'}}" name="config[triagem]" value="1">
        Sim
    </button>
    <button type="submit" class="btn btn-sm {{(!$fila->config->triagem) ? 'btn-success' : 'btn-secondary'}}" name="config[triagem]" value="0">
        Não
    </button>
</div>
{!! Form::close(); !!}


</div>
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
