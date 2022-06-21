<button type="button" class="btn btn-primary btn-sm" onclick="json_modal_form()">
    <i class="fas fa-plus"></i> Adicionar Campo
</button>

<!-- Modal -->
<div class="modal fade" id="json-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalShowJson"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowJson">Adicionar novo campo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">
                    {!! Form::open(['route' => ['filas.storetemplate', $fila->id], 'id' => 'template-form', 'method' => 'POST']) !!}
                    {!! Form::token() !!}
                    <div id="template-new" class="form-group row mt-2">
                        <div class="col-2"><strong>Campo</strong></div>
                        <input class="form-control col-9" name="campo">
                    </div>
                    @foreach ($fila->getTemplateFields() as $field)
                        <div class="form-group row mt-2">
                            <div class="col-2"><strong>{{ ucfirst($field) }}</strong></div>
                            
                                @switch($field)
                                    @case('type')
                                    <select class="form-control col-9" name="new[{{ $field }}]">
                                        <option value='text'>Texto</option>
                                        <option value='select'>Caixa de Seleção</option>
                                        <option value='date'>Data</option>
                                        <option value='number'>Número</option>
                                    </select>
                                    @break
                                    @case('validate')
                                    <select class="form-control col-9" name="new[{{ $field }}]">
                                        <option value=''>Sem validação</option>
                                        <option value='required'>Obrigatório</option>
                                        <option value='required|integer'>Obrigatório - Somente números</option>
                                    </select>
                                    @break
                                    @case('can')
                                    <select class="form-control col-9" name="new[{{ $field }}]">
                                        <option value=''>Exibido para todos</option>
                                        <option value='atendente'>Somente Atendentes</option>
                                    </select>
                                    @break
                                    @default
                                    <input class="form-control col-9" name="new[{{ $field }}]">
                                @endswitch
                            
                        </div>
                    @endforeach
                    <div class="text-right mt-2">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button class="btn btn-primary ml-1" type="submit">Salvar</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@section('javascripts_bottom')
    @parent
    <script>
        $(document).ready(function() {
            var jsonForm = $('#json-modal-form');
            json_modal_form = function() {
                jsonForm.modal();
            }
        });

    </script>
@endsection
