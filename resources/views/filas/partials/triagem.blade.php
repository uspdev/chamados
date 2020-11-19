<span class="font-weight-bold">Habilitar triagem </span>
<span data-toggle="tooltip" data-html="true" title="O gerente da fila fará a distribuição dos chamados entre os atendentes/Os atendentes farão auto atribuições por conta própria.">
    <i class="fas fa-question-circle text-primary"></i>
</span><br>

<div class="ml-2">
    {!! Form::open(['url'=>'filas/'.$fila->id, 'name' => 'form_triagem']) !!}
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
