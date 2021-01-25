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
        <div class="form-group form-row">
            <div class="col-3" id="options_select">
                <label class="form-label">Select:</label>
                <button type="button" class="btn btn-sm btn-primary ml-2 mb-2" id="btn_adiciona_select"
                    style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i
                        class="fa fa-plus"></i></button>
                @foreach ($fila->config->status->select as $input)
                    <input class="form-control col-12" type="text" name="config[status][select][]" value="{{ $input ?? '' }}">
                @endforeach
            </div>
            <div class="col-3" id="options_select_cor">
                <label class="form-label">Cor:</label>
                @foreach ($fila->config->status->select_cor as $input)
                    <select name="config[status][select_cor][]" class="form-control col-12">
                        <option value="">Selecione...</option>
                        <option value="danger" class="bg-danger text-white" @if($input == 'danger') selected @endif>Danger</option>
                        <option value="warning" class="bg-warning text-dark" @if($input == 'warning') selected @endif>Warning</option>
                        <option value="primary" class="bg-primary text-white" @if($input == 'primary') selected @endif>Primary</option>
                        <option value="secondary" class="bg-secondary text-white" @if($input == 'secondary') selected @endif>Secondary</option>
                        <option value="success" class="bg-success text-white" @if($input == 'success') selected @endif>Success</option>
                        <option value="info" class="bg-info text-white" @if($input == 'info') selected @endif>Info</div>
                        <option value="dark" class="bg-dark text-white" @if($input == 'dark') selected @endif>Dark</div>
                        <option value="white" class="bg-white text-dark" @if($input == 'white') selected @endif>White</div>
                    </select>
                @endforeach
            </div>
            @if (Gate::check('perfilAdmin'))
                <div class="col-3">
                    <label class="form-label">System:</label><br>
                    @foreach ($fila->config->status->system as $input)
                        <input class="form-control col-12" type="text" name="config[status][system][]"
                            value="{{ $input ?? '' }}">
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div class="mt-3">
    <input class="btn-sm btn-primary" type="submit" name="ok" value="ok">
</div>
{!! Form::close() !!}

@section('javascripts_bottom')
    @parent
    <script>
        $(document).on('click', '#btn_adiciona_select', function() {
            var novoItem =
                '<input class="form-control col-12" type="text" name="config[status][select][]" value="">';
            $("#options_select").append(novoItem);

            var novoItemCor = '<select name="config[status][select_cor][]" class="form-control col-12"><option value="">Selecione...</option><option value="danger" class="bg-danger text-white">Danger</option><option value="warning" class="bg-warning text-dark">Warning</option><option value="primary" class="bg-primary text-white">Primary</option><option value="secondary" class="bg-secondary text-white">Secondary</option><option value="success" class="bg-success text-white">Success</option><option value="info" class="bg-info text-white">Info</div><option value="dark" class="bg-dark text-white">Dark</div><option value="white" class="bg-white text-dark">White</div></select>';
            $("#options_select_cor").append(novoItemCor);
        });

    </script>
@endsection
