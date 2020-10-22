<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#VinculadoModal">
    <i class="fas fa-plus"></i> Adicionar
</button>

<!-- Modal -->
<div class="modal fade" id="VinculadoModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vincular Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">

                    {!! Form::open(['url'=>'chamados/'.$chamado->nro.'/vinculado']) !!}                    
                    @method('post')
                    @csrf
    
                    <div class="form-group row">
                        {{ Form::label('slct_chamados', 'Chamados', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('slct_chamados',[], null,['class' => 'form-control', 'placeholder'=>'Digite o número do chamado ou assunto..']) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ Form::label('tipo', 'Tipo de Acesso', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('tipo',['leitura'=>'Leitura', 'escrita'=>'Escrita'], 'Leitura', 
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
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        var chamadosForm = $('#VinculadoModal');

        add_modal_form = function() {
            chamadosForm.modal();
        }

        chamadosForm.find(':input[name=slct_chamados]').select2({
            ajax: {
                url: 'chamados/listarChamadosAjax'
                , dataType: 'json'
            }
            , dropdownParent: chamadosForm
            , minimumInputLength: 3
            , theme: 'bootstrap4'
            , wdth: 'resolve'
            , language: 'pt_br'
        })

    })

</script>
@endsection
