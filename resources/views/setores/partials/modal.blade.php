<!-- Modal que atende adicionar e editar setores -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Adicionar/Editar setores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="list_table_div_form">
                    {{ html()->form('POST', '')->open() }}
                    {{ html()->hidden('id') }}

                    @foreach ($fields as $col)
                    @if (empty($col['type']) || $col['type'] == 'text')
                    @include('common.list-table-modal-text')

                    @elseif ($col['type'] == 'select')
                    @include('common.list-table-modal-select')

                    @endif
                    @endforeach
                    <div class="text-right">
                        {{-- vamos adicionar o botão do modal quando for o caso --}}
                        @yield('form-dismiss-btn')

                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                    {{ html()->form()->close() }}
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

        $('#modalForm').on('shown.bs.modal', function() {
            $(this).find(':input[type=text]').filter(':visible:first').focus();
        })

        edit_form = function(id) {
            $.get('setores/' + id
                , function(row) {
                    console.log(row);
                    // mudando para PUT
                    $('#modalForm :input').filter("input[name='_method']").val('PUT');

                    // preenchendo o form com os valores a serem editados
                    var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
                    inputs.each(function() {
                        $(this).val(row[this.name]);
                        console.log(this.name);
                    });

                    // Ajustando action
                    $('#modalForm').find('form').attr('action', 'setores/' + id);

                    // Ajustando o title
                    $('#modalLabel').html('Editar setor');

                    $("#modalForm").modal();
                    console.log('inputs', inputs);
                });
        }

        add_form = function(id) {

            $("#modalForm :input").filter("input[type='text']").val('');

            // preenchendo o form com os valores a serem editados
            $("#modalForm select").val(id);

            // Ajustando action
            $('#modalForm').find('form').attr('action', 'setores');

            $('#modalLabel').html('Adicionar setor');
            $('#modalForm :input').filter("input[name='_method']").val('POST');

            $("#modalForm").modal();

        }

    })

</script>
@endsection
