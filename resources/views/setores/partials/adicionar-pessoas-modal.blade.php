<!-- Modal para adicionar pessoas ao setor -->
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
                    {!! Form::open(['url'=>$modal['url']]) !!}
                    @method('POST')
                    {{ Form::hidden('id') }}

                    @foreach ($modal['fields'] as $col)
                    @if (empty($col['type']) || $col['type'] == 'text')
                    @include('common.list-table-modal-text')

                    @elseif ($col['type'] == 'select')
                    @include('common.list-table-modal-select')

                    @endif
                    @endforeach
                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                    {!! Form::close(); !!}
                </div>

            </div>
            {{-- <div class="modal-footer"></div> --}}
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
            }
            , dropdownParent: pessoasForm
            , minimumInputLength: 4
            , theme: 'bootstrap4'
            , wdth: 'resolve'
            , language: 'pt_br'
        })

        adicionar_pessoas = function(id) {
            pessoasForm.modal();
            pessoasForm.find('form').attr('action', 'setores/' + id + '/pessoas')
            console.log('abriu modal pessoa')
        }

        pessoasForm.on('shown.bs.modal', function() {
            $oSelect2.select2('open')
        })
    })

</script>
@endsection
