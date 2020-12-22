<div class="font-weight-bold">
    <i class="fas fa-cogs"></i> Configurações
</div>

{!! Form::open(['url' => 'filas/' . $fila->id, 'name' => 'form_config']) !!}
@method('put')

<div class="ml-2 mt-2">
    <div class="font-weight-bold">
        Triagem
        <span data-toggle="tooltip" data-html="true" title="O gerente da fila fará a distribuição dos chamados entre os atendentes/Os atendentes farão auto atribuições por conta própria.">
            <i class="fas fa-question-circle text-primary"></i>
        </span>
    </div>

    <div class="ml-2">
        <span class="text-muted mr-2">habilitar triagem:</span>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[triagem]" value="1" {{ $fila->config->triagem ? 'checked' : '' }}>
                Sim
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[triagem]" value="0" {{ $fila->config->triagem ? '' : 'checked' }}>
                Não
            </label>
        </div>
    </div>
</div>

<div class="ml-2 mt-3">
    <div class="font-weight-bold">Visibilidade
        <span data-toggle="tooltip" data-html="true" title="Controla quem poderá abrir chamados nessa fila.">
            <i class="fas fa-question-circle text-primary"></i>
        </span>
    </div>

    <div class="ml-2">
        <span class="text-muted mr-2">por usuários:</span>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="config[visibilidade][alunos]" value="1" {{ $fila->config->visibilidade->alunos ? 'checked' : '' }}>
                alunos
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="config[visibilidade][servidores]" value="1" {{ $fila->config->visibilidade->servidores ? 'checked' : '' }}>
                servidores
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="config[visibilidade][gerentes]" value="1" {{ $fila->config->visibilidade->gerentes ? 'checked' : '' }}>
                gerentes de filas e setores
            </label>
        </div>
    </div>

    <div>
        <div class="btn-group ml-2">
            <span class="text-muted mr-2">por setor:</span>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="config[visibilidade][setores]" value="interno" {{ $fila->config->visibilidade->setores == 'interno' ? 'checked' : '' }}>
                    {{ $fila->setor->sigla }}
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="config[visibilidade][setores]" value="todos" {{ $fila->config->visibilidade->setores == 'todos' ? 'checked' : '' }}>
                    todos
                </label>
            </div>
        </div>
    </div>
</div>

<div class="ml-2 mt-3">
    <div class="font-weight-bold">
        Patrimônio
        <span data-toggle="tooltip" data-html="true" title="O fornecimento de pelo menos 1 número de patrimônio é obrigatório">
            <i class="fas fa-question-circle text-primary"></i>
        </span>
    </div>

    <div class="ml-2">
        <span class="text-muted mr-2">Obrigatório:</span>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[patrimonio]" value="1" {{ $fila->config->patrimonio ? 'checked' : '' }}>
                Sim
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[patrimonio]" value="0" {{ $fila->config->patrimonio ? '' : 'checked' }}>
                Não
            </label>
        </div>
    </div>
</div>

<div class="mt-3">
    <input type="submit" name="ok" value="ok">
</div>
{!! Form::close() !!}
