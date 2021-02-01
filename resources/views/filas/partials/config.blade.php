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
            <div class="col-6" id="options_select_cor">
                <label class="form-label">Cor:</label>
                @foreach ($fila->config->status->select_cor as $input)
                    @php
                    $id = rand();
                    @endphp
                    <div class="form-inline">
                        <select name="config[status][select_cor][]" class="form-control col-6" id="select_{{ $id }}">
                            <option value="">Selecione...</option>
                            <option value="bg-danger text-white" class="bg-danger text-white" @if ($input == 'bg-danger text-white') selected @endif>
                                Danger</option>
                            <option value="bg-warning text-dark" class="bg-warning text-dark" @if ($input == 'bg-warning text-dark') selected @endif>
                                Warning</option>
                            <option value="bg-primary text-white" class="bg-primary text-white" @if ($input == 'bg-primary text-white') selected @endif>
                                Primary</option>
                            <option value="bg-secondary text-white" class="bg-secondary text-white" @if ($input == 'bg-secondary text-white') selected @endif>
                                Secondary</option>
                            <option value="bg-success text-white" class="bg-success text-white" @if ($input == 'bg-success text-white') selected @endif>
                                Success</option>
                            <option value="bg-info text-white" class="bg-info text-white" @if ($input == 'bg-info text-white') selected @endif>Info
                            </option>
                            <option value="bg-dark text-white" class="bg-dark text-white" @if ($input == 'bg-dark text-white') selected @endif>Dark
                            </option>
                            <option value="bg-white text-dark" class="bg-white text-dark" @if ($input == 'bg-white text-dark') selected @endif>White
                            </option>
                        </select>
                        <span class="badge ml-2 {{ $input ?? '' }}" id="span_{{ $id }}">Teste</span>
                        <span class="ml-2 {{ $input ?? '' }}" id="span2_{{ $id }}" data-toggle="tooltip" title="Teste">
                            <i class="fas fa-circle"></i> </span>
                        <button type="button" class="btn btn-sm btn-danger ml-2" id="btn_deleta_select"
                            style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i
                                class="fa fa-minus"></i></button>
                    </div>
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
    <input class="btn-sm btn-primary" id="submit" type="submit" name="ok" value="ok">
</div>
{!! Form::close() !!}

@section('javascripts_bottom')
    @parent
    <script>
        $(document).on('click', '#btn_adiciona_select', function() {
            var novoItem = '<input class="form-control col-12" type="text" name="config[status][select][]" value="">';
            $("#options_select").append(novoItem);
            var id = Math.floor(Math.random() * 1000 + 1);
            var novoItemCor = '<div class="form-inline">';
            novoItemCor += '<select name="config[status][select_cor][]" class="form-control col-6" id="select_' + id + '">';
            novoItemCor += '<option value="">Selecione...</option>';
            novoItemCor += '<option value="bg-danger text-white" class="bg-danger text-white">Danger</option>';
            novoItemCor += '<option value="bg-warning text-dark" class="bg-warning text-dark">Warning</option>';
            novoItemCor += '<option value="bg-primary text-white" class="bg-primary text-white">Primary</option>';
            novoItemCor += '<option value="bg-secondary text-white" class="bg-secondary text-white">Secondary</option>';
            novoItemCor += '<option value="bg-success text-white" class="bg-success text-white">Success</option>';
            novoItemCor += '<option value="bg-info text-white" class="bg-info text-white">Info</option>';
            novoItemCor += '<option value="bg-dark text-white" class="bg-dark text-white">Dark</option>';
            novoItemCor += '<option value="bg-white text-dark" class="bg-white text-dark">White</option>';
            novoItemCor += '</select>';
            novoItemCor += '<span class="badge ml-2" id="span_' + id + '">Teste</span>';
            novoItemCor += '<span class="ml-2" data-toggle="tooltip" title="Teste" id="span2_' + id + '"> <i class="fas fa-circle"></i> </span>';
            novoItemCor += '<button type="button" class="btn btn-sm btn-danger ml-2" id="btn_deleta_select" style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i class="fa fa-minus"></i></button>';
            novoItemCor += '</div>';
            $("#options_select_cor").append(novoItemCor);
        });

        $(document).on('click', '#btn_deleta_select', function() {
            if (confirm("Tem certeza que quer apagar?")) {
                var inputs = document.getElementsByName('config[status][select][]');
                var selects = document.getElementsByName('config[status][select_cor][]');
                var i = 0,
                    j = 0,
                    s;
                selects.forEach(element => {
                    if (element.parentElement == this.parentElement) {
                        var node = element.parentElement;
                        node.parentNode.removeChild(node);
                        s = i;
                    }
                    i++;
                });
                inputs.forEach(element => {
                    if (s == j) {
                        var node = element;
                        node.parentNode.removeChild(node);
                    }
                    j++
                });
            }
        });

        $(document).on('change', 'select', function(e) {

            var optionSelected, otherOptionSelected, match = 0;
            if (this.name == 'config[status][select_cor][]') {
                optionSelected = $("option:selected", this).val();
                $('#' + 'span_' + this.id.substr(7)).removeClass().addClass('badge ml-2 ' + optionSelected);
                $('#' + 'span2_' + this.id.substr(7)).removeClass().addClass('ml-2 ' + optionSelected);
            }
            $('select').each(function(index, element) {
                if (this.name == 'config[status][select_cor][]') {
                    otherOptionSelected = $("option:selected", this).val();
                    if (optionSelected == otherOptionSelected) {
                        match++;
                    }
                }
            });
            if (match > 1) {
                alert("Favor Selecionar cores diferentes para cada Status!");
                $('#submit').hide();
            } else {
                $('#submit').show();
            }
        });

    </script>
@endsection
