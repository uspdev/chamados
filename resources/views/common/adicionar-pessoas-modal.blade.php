<!-- Modal para adicionar pessoas ao setor -->
{{-- é necessário passar
    $modal['url']
    $modal['title']
--}}
<div class="modal fade" id="adicionar-pessoas-modal" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">{{ $modal['title'] }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="list_table_div_form">
                    {{ html()->form('POST', $modal['url'])->open() }}

                    <div class="form-group row">
                        {{ html()->label('Nome', 'codpes')->class(['col-form-label', 'col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ html()->select('codpes', [], null)->class(['form-control']) }}
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        var pessoasForm = $('#adicionar-pessoas-modal')
        var $oSelect2 = pessoasForm.find(':input[name=codpes]')

        $oSelect2.select2({
            ajax: {
                url: 'search/partenome'
                , dataType: 'json'
                , delay: 1000
            }
            , dropdownParent: pessoasForm
            , minimumInputLength: 4
            , theme: 'bootstrap4'
            , width: '100%'
            , language: 'pt_br'
        })

        adicionar_pessoas = function(id) {
            pessoasForm.modal();
            var action = pessoasForm.find('form').attr('action')
            pessoasForm.find('form').attr('action', action + '/' + id + '/pessoas')
            console.log('abriu modal pessoa')
        }

        pessoasForm.on('shown.bs.modal', function() {
            $oSelect2.select2('open')
        })
    })

</script>
@endsection
