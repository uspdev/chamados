<button type="button" class="btn btn-sm btn-success" onclick="add_modal_form()">
    <i class="fas fa-plus"></i> Novo
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
                    {{ html()->form('POST', 'users')->open() }}
                    <div class="form-group row">
                        {{ html()->label('Nome', 'codpes')->class(['col-form-label', 'col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ html()->select('codpes',[], null)->class(['form-control', 'col-3'])->placeholder('Digite nome ..') }}
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                    {{ html()->form()->close() }}
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
            , width: '100%'
            , language: 'pt_br'
        })

    })

</script>
@endsection
