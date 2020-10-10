<?php #dd($data);
$data->modal = true; ?>

{{-- o botao vai aqui em cima para poder incluir no form --}}
@section('form-dismiss-btn')
<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
@endsection

<!-- Modal -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">{{ $data->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                @include('common.list-table-form')

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
            $.get('{{ $data->url }}/' + id, function(row) {
                console.log(row);
                // mudando para PUT
                $('#modalForm :input').filter("input[name='_method']").val('PUT');

                // preenchendo o form com os valores a serem editados
                var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
                inputs.each(function() {
                    $(this).val(row[this.name]);
                });

                // colocando o id no action
                var action = $("#modalForm").find('form').attr('action');
                $("#modalForm").find('form').attr('action', action + '/' + id);
                $("#modalForm").modal();

                console.log('inputs', inputs);
            });
        }

    })

</script>
@endsection
