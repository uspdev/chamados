<div class="font-weight-bold">
    <i class="fas fa-cogs"></i> Configurações
</div>

{!! Form::open(['url' => 'filas/' . $fila->id, 'name' => 'form_config']) !!}
@method('put')

<div class="ml-2 mt-2">
    <span class="font-weight-bold">Triagem</span>
    @include('ajuda.filas.config-triagem')

    <div class="ml-2">
        <span class="text-muted mr-2">habilitar triagem:</span>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[triagem]" value="1"
                    {{ $fila->config->triagem ? 'checked' : '' }}>
                Sim
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[triagem]" value="0"
                    {{ $fila->config->triagem ? '' : 'checked' }}>
                Não
            </label>
        </div>
    </div>
</div>

<div class="ml-2 mt-3">
    <span class="font-weight-bold">Visibilidade</span>
    @include('ajuda.filas.config-visibilidade')

    <div class="ml-2">
        <span class="text-muted mr-2">pessoas:</span>
        <div class="ml-3">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input disabled class="form-check-input" type="checkbox" name="config[visibilidade][alunos]"
                        value="1" {{ $fila->config->visibilidade->alunos ? 'checked' : '' }}>
                    alunos
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][servidores]" value="1"
                        {{ $fila->config->visibilidade->servidores ? 'checked' : '' }}>
                    servidores
                </label>
            </div>
            <br>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][setor_gerentes]"
                        value="1" {{ $fila->config->visibilidade->setor_gerentes ? 'checked' : '' }}>
                    gerentes de setor
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="config[visibilidade][fila_gerentes]" value="1"
                        {{ $fila->config->visibilidade->fila_gerentes ? 'checked' : '' }}>
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
                    <input class="form-check-input" type="radio" name="config[visibilidade][setores]" value="interno"
                        {{ $fila->config->visibilidade->setores == 'interno' ? 'checked' : '' }}>
                    interno ({{ $fila->setor->sigla }})
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="radio" name="config[visibilidade][setores]" value="todos"
                        {{ $fila->config->visibilidade->setores == 'todos' ? 'checked' : '' }}>
                    todos
                </label>
            </div>
        </div>
    </div>
</div>

<div class="ml-2 mt-3">
    <span class="font-weight-bold">Patrimônio</span>
    @include('ajuda.filas.config-patrimonio')

    <div class="ml-2">
        <span class="text-muted mr-2">Obrigatório:</span>

        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[patrimonio]" value="1"
                    {{ $fila->config->patrimonio ? 'checked' : '' }}>
                Sim
            </label>
        </div>
        <div class="form-check form-check-inline">
            <label class="form-check-label">
                <input class="form-check-input" type="radio" name="config[patrimonio]" value="0"
                    {{ $fila->config->patrimonio ? '' : 'checked' }}>
                Não
            </label>
        </div>
    </div>
</div>

<div class="ml-2 mt-3">
    <span class="font-weight-bold">Status</span>
    @include('ajuda.filas.config-status')

    <div class="ml-2">
        <div class="form-group">
            <label class="form-label">Select:</label><br>
            @foreach ($fila->config->status->select as $input)
                <input class="form-control" type="text" name="config[status][select][]" value="{{ $input ?? '' }}"><br>
            @endforeach
            <input class="form-control" type="text" name="config[status][select][]" value=""><br>
        </div>

        <div class="form-group">
            <label class="form-label">System:</label><br>
            @foreach ($fila->config->status->system as $input)
                <input class="form-control" type="text" name="config[status][system][]" value="{{ $input ?? '' }}"><br>
            @endforeach
            <input class="form-control" type="text" name="config[status][system][]" value="">
        </div>
    </div>
</div>

<div class="mt-3">
    <input type="submit" name="ok" value="ok">
</div>
{!! Form::close() !!}
