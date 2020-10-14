<button type="button" class="btn btn-sm btn-light text-primary" onclick="add_modal_form()">
    <i class="fas fa-plus"></i> Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="common-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">Adicionar pessoas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="list_table_div_form">
                    {!! Form::open( ['url'=>$data->url.'/'.$data->row->id.'/pessoas']) !!}
                    @method('POST')
                    @csrf

                    <div class="form-group row">
                        {{ Form::label('codpes', 'Nome', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('codpes',[], null,['class' => 'form-control', 'placeholder'=>'Digite nome ..']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('funcao', 'Função', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('funcao',['Gerente'=>'Gerente', 'Atendente'=>'Atendente'], 'Atendente', 
                            ['class' => 'form-control col-3']) }}
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                    {!! Form::close(); !!}
                </div>

            </div>
        </div>
        {{-- <div class="modal-footer"></div> --}}
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        var pessoasForm = $('#common-modal-form');

        add_modal_form = function() {
            pessoasForm.modal();
        }

        pessoasForm.find(':input[name=codpes]').select2({
            ajax: {
                url: 'search/partenome'
                , dataType: 'json'
            }
            , dropdownParent: pessoasForm
            , minimumInputLength: 4
            , theme: 'bootstrap4'
            , wdth: 'resolve'
            , language: 'pt_br'
        })

    })

</script>
@endsection
