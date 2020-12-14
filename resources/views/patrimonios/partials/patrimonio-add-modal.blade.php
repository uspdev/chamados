<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#PatrimonioModal">
    <i class="fas fa-plus"></i> Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="PatrimonioModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adicionar Patrimônio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">

                    {!! Form::open(['url' => 'chamados/' . $chamado->id . '/patrimonios']) !!}
                    @method('post')
                    @csrf

                    <div class="form-group row">
                        {{ Form::label('numpat', 'Patrimônio', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('numpat', [], null, ['class' => 'form-control', 'placeholder' => 'Digite um número de patrimônio..']) }}
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
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

            var patrimoniosForm = $('#PatrimonioModal');

            add_modal_form = function() {
                patrimoniosForm.modal();
            }

            patrimoniosForm.find(':input[name=numpat]').select2({
                ajax: {
                    url: 'chamados/listarPatrimoniosAjax',
                    dataType: 'json'
                },
                dropdownParent: patrimoniosForm,
                minimumInputLength: 9,
                theme: 'bootstrap4',
                wdth: 'resolve',
                language: 'pt_br'
            })
        })

    </script>
@endsection
