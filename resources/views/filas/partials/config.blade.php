@section('styles')
@parent
<style>
    #card-fila-config {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }

</style>
@endsection

<div class="card mb-3" id="card-fila-config">
    <div class="card-header">
        <i class="fas fa-cogs"></i> Configurações
    </div>
    <div class="card-body">

        {!! Form::open(['url' => 'filas/' . $fila->id, 'name' => 'form_config']) !!}
        @method('put')

        <div class="ml-2 mt-2">
            <span class="font-weight-bold">Triagem</span>
            @include('ajuda.filas.config-triagem')

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
            <span class="font-weight-bold">Visibilidade</span>
            @include('ajuda.filas.config-visibilidade')

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
                    <div class="form-check form-check-inline">
                        <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" name="config[visibilidade][todos]" value="1" {{ $fila->config->visibilidade->todos ? 'checked' : '' }}>
                            todos (USP)
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
            <span class="font-weight-bold">Patrimônio</span>
            @include('ajuda.filas.config-patrimonio')

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

        <div class="ml-2 mt-3" id="config_status">
            <span class="font-weight-bold">Estados</span>
            @include('ajuda.filas.config-status')
            <button type="button" class="btn btn-sm btn-light text-primary" id="btn_add_status"><i class="fas fa-plus"></i> Adicionar</button>

            <div class="form-row">
                <div class="col-12 form-group ml-2">
                    @foreach ($fila->config->status as $input)
                    <div class="form-inline mb-2">
                        <input class="form-control col-5" type="text" name="config[status][select][]" value="{{ $input->label ?? '' }}">
                        <select name="config[status][select_cor][]" class="form-control col-3 ml-2">
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
                        <div style="flex: 0 0 150px;">
                            <span class="ml-2 badge badge-{{ $input->color ?? '' }}">Teste</span>
                            <span class="ml-2 circulo text-{{ $input->color ?? '' }}"><i class="fas fa-circle"></i></span>
                            <button type="button" class="btn btn-sm btn-danger btn_del_status ml-2" data-toggle="tooltip" title="Remover">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-3">
            <input class="btn-sm btn-primary" id="config_submit" type="submit" name="ok" value="Salvar Configurações">
        </div>
        {!! Form::close() !!}
    </div>
</div>

<template id="form-inline">
    <div class="form-inline mb-2">
        <input class="form-control col-5" type="text" name="config[status][select][]" value="">
        <select name="config[status][select_cor][]" class="form-control col-3 ml-2">
            <option value="">Selecione...</option>
            <option value="danger" class="bg-danger text-dark">Danger</option>
            <option value="warning" class="bg-warning text-dark">Warning</option>
            <option value="primary" class="bg-primary text-white">Primary</option>
            <option value="secondary" class="bg-secondary text-white">Secondary</option>
            <option value="success" class="bg-success text-white">Success</option>
            <option value="info" class="bg-info text-white">Info</option>
            <option value="dark" class="bg-dark text-white">Dark</option>
            <option value="white" class="bg-white text-dark">White</option>
        </select>
        <div style="flex: 0 0 150px;">
            <span class="ml-2 badge">Teste</span>
            <span class="ml-2 circulo"><i class="fas fa-circle"></i></span>
            <button type="button" class="btn btn-sm btn-danger btn_del_status ml-2" data-toggle="tooltip" title="Remover">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>

</template>

@section('javascripts_bottom')
@parent
<script>
    // para config_status
    $(document).on('click', '#btn_add_status', function() {
        var form_group = $('#config_status').find('.form-group')
        form_group.append($('#form-inline').html())
    });

    $(document).on('click', '.btn_del_status', function() {
        if (confirm("Tem certeza que quer apagar?")) {
            var form = $(this).closest('.form-inline')
            form.remove();
        }
    });

    // talez tenha de limitar para o select do status
    $(document).on('change', 'select', function(e) {
        console.log('select change to ', this.value);
        var form = $(this).closest('.form-inline')
        form.find('.badge').removeClass().addClass('ml-2 badge badge-' + this.value)
        form.find('.circulo').removeClass().addClass('ml-2 circulo text-' + this.value)
    });

</script>
@endsection
