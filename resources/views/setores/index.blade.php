@extends('master')

@section('content')
@parent
<br>
<div class="mb-2">
    <span class="h3">{{ $data->title }}</span>
    <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
        <i class="fas fa-plus"></i> Novo
    </button>
</div>

<!-- Modal -->
@include('common.list-table-modal')

@include('common.list-table')

@endsection

@section('javascripts_bottom')
<script>
    $(document).ready(function() {

        $('#modalForm').on('shown.bs.modal', function() {
            $(this).find(':input[type=text]').filter(':visible:first').focus();
        })

        add_form = function() {

            // limpando os inputs
            var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
            inputs.each(function() {
                $(this).val('');
            });

            $("#modalForm").modal();
        }

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
            });

            var action = $("#modalForm").find('form').attr('action');
            $("#modalForm").find('form').attr('action', action + '/' + id);
            $("#modalForm").modal();
        }

        cancel_form = function() {
            $('.form-div').slideUp();

        }

    })

</script>
@endsection
