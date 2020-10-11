<span class="text-muted">Setor:</span> {{ $data->row['setor']->sigla }}<br>
<span class="text-muted">Nome:</span> {{ $data->row['nome'] }}<br>
<span class="text-muted">Descrição:</span> {{ $data->row['descricao'] }}<br>
<br>
<span class="text-muted">Público alvo:</span> Todos (alunos GR, alunos PG/PD, servidores, docentes)<br>
<span class="text-muted">Visibilidade:</span> Todos os setores<br>

<span class="text-muted">Formulário personalizado</span>
<div class="ml-2">
    @if(!empty($data->row['template']))
    @foreach(json_decode($data->row['template']) as $field=>$value)
    {{ $value->label }}<br>
    @endforeach
    @else
    Sem formulário personalizado
    @endif
</div>
