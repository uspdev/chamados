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
            <div class="col-6" id="options_select">
                <label class="form-label">Select:</label>
                <button type="button" class="btn btn-sm btn-primary ml-2 mb-2" id="btn_adiciona_select"
                    style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i
                        class="fa fa-plus"></i></button>
                @foreach ($fila->config->status as $input)
                    <div class="form-inline mb-2">    
                        <input class="form-control col-4" type="text" name="config[status][select][]" value="{{ $input->label ?? '' }}">
                        @php
                        $id = rand();
                        @endphp
                        <select name="config[status][select_cor][]" class="form-control col-4 ml-2" id="select_{{ $id }}">
                            <option value="">Selecione...</option>
                            <option value="danger" class="bg-danger text-dark" @if ($input->color == 'danger') selected @endif>Danger</option>
                            <option value="warning" class="bg-warning text-dark" @if ($input->color == 'warning') selected @endif>Warning</option>
                            <option value="primary" class="bg-primary text-white" @if ($input->color == 'primary') selected @endif>Primary</option>
                            <option value="secondary" class="bg-secondary text-white" @if ($input->color == 'secondary') selected @endif>Secondary</option>
                            <option value="success" class="bg-success text-white" @if ($input->color == 'success') selected @endif>Success</option>
                            <option value="info" class="bg-info text-white" @if ($input->color == 'info') selected @endif>Info</option>
                            <option value="dark" class="bg-dark text-white" @if ($input->color == 'dark') selected @endif>Dark</option>
                            <option value="white" class="bg-white text-dark" @if ($input->color == 'white') selected @endif>White</option>
                        </select>
                        <span class="ml-2 badge badge-{{ $input->color ?? '' }}" id="span_{{ $id }}">Teste</span>
                        <span class="ml-2 text-{{ $input->color ?? '' }}" id="span2_{{ $id }}" data-toggle="tooltip" title="Teste"><i class="fas fa-circle"></i></span>
                        <button type="button" class="btn btn-sm btn-danger ml-2" id="btn_deleta_select" style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i class="fa fa-minus"></i></button>
                    </div>
                @endforeach
            </div>
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
            var id = Math.floor(Math.random() * 1000 + 1);
            var novoItem = '<div class="form-inline mb-2">';
            novoItem += '<input class="form-control col-4" type="text" name="config[status][select][]" value="">';
            novoItem += '<select name="config[status][select_cor][]" class="form-control col-4 ml-2" id="select_' + id + '">';
            novoItem += '<option value="">Selecione...</option>';
            novoItem += '<option value="danger" class="bg-danger text-white">Danger</option>';
            novoItem += '<option value="warning" class="bg-warning text-dark">Warning</option>';
            novoItem += '<option value="primary" class="bg-primary text-white">Primary</option>';
            novoItem += '<option value="secondary" class="bg-secondary text-white">Secondary</option>';
            novoItem += '<option value="success" class="bg-success text-white">Success</option>';
            novoItem += '<option value="info" class="bg-info text-white">Info</option>';
            novoItem += '<option value="dark" class="bg-dark text-white">Dark</option>';
            novoItem += '<option value="white" class="bg-white text-dark">White</option>';
            novoItem += '</select>';
            novoItem += '<span class="ml-2 badge" id="span_' + id + '">Teste</span>';
            novoItem += '<span class="ml-2" data-toggle="tooltip" title="Teste" id="span2_' + id + '"> <i class="fas fa-circle"></i> </span>';
            novoItem += '<button type="button" class="btn btn-sm btn-danger ml-2" id="btn_deleta_select" style="height: 22px; padding-top: 0px; padding-bottom: 0px; border-top-width: 0px; border-bottom-width: 0px; margin-bottom: 0px;"><i class="fa fa-minus"></i></button>';
            novoItem += '</div>';
            $("#options_select").append(novoItem);
        });

        $(document).on('click', '#btn_deleta_select', function() {
            if (confirm("Tem certeza que quer apagar?")) {
                var item = document.getElementsByName('config[status][select_cor][]');
                item.forEach(element => {
                    if (element.parentElement == this.parentElement) {
                        var node = element.parentElement;
                        node.parentNode.removeChild(node);
                    }
                });
            }
        });

        $(document).on('change', 'select', function(e) {

            var optionSelected, otherOptionSelected, match = 0;
            if (this.name == 'config[status][select_cor][]') {
                optionSelected = $("option:selected", this).val();
                $('#' + 'span_' + this.id.substr(7)).removeClass().addClass('ml-2 badge badge-' + optionSelected);
                $('#' + 'span2_' + this.id.substr(7)).removeClass().addClass('ml-2 text-' + optionSelected);
            }
            $('select').each(function(index, element) {
                if (this.name == 'config[status][select_cor][]') {
                    otherOptionSelected = $("option:selected", this).val();
                    if (optionSelected == otherOptionSelected) {
                        match++;
                    }
                }
            });
        });

    </script>
@endsection
