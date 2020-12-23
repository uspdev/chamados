<div class="font-weight-bold">
    <i class="fas fa-cogs"></i> Configurações
</div>

{!! Form::open(['url' => 'filas/' . $fila->id, 'name' => 'form_config']) !!}
@method('put')

<div class="ml-2 mt-2">
    <span class="font-weight-bold">Triagem</span>
    <a data-toggle="collapse" href="#ajuda_triagem">
        <i class="fas fa-question-circle text-primary"></i>
    </a>
    <div class="collapse" id="ajuda_triagem">
        <div class="card-body card-ajuda">
            <b>Ajuda para triagem</b><br>
            Se habilitado, o gerente da fila deverá atribuir novos chamados
            para um atendente. Se desabilitado, o atendente poderá auto-atribuir o chamado.
        </div>
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
        <a data-toggle="collapse" href="#ajuda_visibilidade">
            <i class="fas fa-question-circle text-primary"></i>
        </a>
    </div>
    <div class="collapse" id="ajuda_visibilidade">
        <div class="card-body card-ajuda">
            <b>Ajuda para visibilidade</b><br>
            Controla quem pode criar chamados nessa fila.<br>
            No grupo pessoas, selecione a categoria de pessoas que deseja liberar.<br>
            No grupo setores, escolha entre interno (somente pessoas vinculadas ao setor),
            ou todos.<br>
            <br>
            <b>Exemplo 1</b>: Criar fila para uso interno em um departamento acadêmico:
            selecione <b>servidores</b> e <b>interno</b>.<br>
            <b>Exemplo 2</b>: Criar fila em um setor administrativo que ficará disponível
            aos chefes e secretários da Unidade: selecione <b>gerentes de setor</b> e <b>todos</b>.<br>
            <b>Exemplo 3</b>: Criar fila para uso somente das pessoas cadastradas na fila:
            selecione <b>interno</b>.<br>
            <br>
            OBS.: A liberação para alunos estará disponível em breve.
        </div>
    </div>
    <div class="ml-2">
        <span class="text-muted mr-2">pessoas:</span>
        <div class="ml-3">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input disabled class="form-check-input" type="checkbox" name="config[visibilidade][alunos]" value="1" {{ $fila->config->visibilidade->alunos ? 'checked' : '' }}>
                    alunos
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][servidores]" value="1" {{ $fila->config->visibilidade->servidores ? 'checked' : '' }}>
                    servidores
                </label>
            </div>
            <br>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][setor_gerentes]" value="1" {{ $fila->config->visibilidade->setor_gerentes ? 'checked' : '' }}>
                    gerentes de setor
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][fila_gerentes]" value="1" {{ $fila->config->visibilidade->fila_gerentes ? 'checked' : '' }}>
                    gerentes de fila
                </label>
            </div>
        </div>
    </div>
    <div>
        <div class="btn-group ml-2">
            <span class="text-muted mr-2">setores:</span>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="config[visibilidade][setores]" value="interno" {{ $fila->config->visibilidade->setores == 'interno' ? 'checked' : '' }}>
                    interno ({{ $fila->setor->sigla }})
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
