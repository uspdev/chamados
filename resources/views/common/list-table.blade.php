<div class="row">
    <div class="col-md-12 form-inline">

        <span class="h4 mt-2">{{ $data->title }}</span>

        <div class="input-group col-2">
            <input class="form-control form-control-sm" type="text" id="dt-search" placeholder="Filtrar ..">
            <div class="input-group-append">
                <button class="btn btn-sm btn-outline-secondary" id="dt-search-clear">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-success" onclick="add_form()">
            <i class="fas fa-plus"></i> Novo
        </button>

    </div>
</div>

@if (!$data)
É necessário enviar a variável $data para que este componente funcione.
@else

<?php #dd($data); ?>

{{-- incluindo o modal com form  --}}
@include('common.list-table-modal')

<table class="table table-striped table-sm table-hover datatable-nopagination">
    <thead>
        <tr>
            @if ($data->showId)
            <td></td>
            @endif

            @foreach ($data->fields as $col)
            <th>{{ $col['label'] ?? $col['name'] }}</th>
            @endforeach

            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data->rows as $row)
        <tr>
            @if ($data->showId)
            <td>{{ $row->id }}</td>
            @endif

            @foreach ($data->fields as $col)
            <td>@include('common/list-table-col-name')</td>
            @endforeach

            <td style="width: 80px;">
                <div class="row">
                    <div class="col">
                        {{-- Botão de editar --}}
                        <button class="btn btn-light text-primary py-0 px-0" data-toggle="tooltip" title="Editar" onclick="edit_form({{ $row->id }})">
                            <i class="far fa-edit"></i>
                        </button>
                    </div>
                    <div class="col">
                        {{-- Form de deletar --}}
                        <form method="POST" action="/{{ $data->url }}/{{ $row->id }}" class="form-inline pull-left">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-light text-danger py-0 px-0 delete-item" data-toggle="tooltip" title="Remover">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </td>
            @endforeach
    </tbody>
</table>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.datatable-nopagination').DataTable({
            dom: 'ti'
        });

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
            $.get('{{ $data->url }}/' + id + '?parent=1', function(row) {
                console.log(row);
                // mudando para PUT
                $('#modalForm :input').filter("input[name='_method']").val('PUT');

                // preenchendo o form com os valores a serem editados
                var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
                inputs.each(function() {
                    $(this).val(row[this.name]);
                });
                console.log('inputs', inputs);
            });

            var action = $("#modalForm").find('form').attr('action');
            $("#modalForm").find('form').attr('action', action + '/' + id);
            $("#modalForm").modal();
        }

        $('#dt-search').focus();

        $('#dt-search').keyup(function() {
            oTable.search($(this).val()).draw();
        })

        $('#dt-search-clear').on('click', function() {
            $('#dt-search').val('').trigger('keyup');
            $('#dt-search').focus();
        })

    })

</script>
@endsection

@endif
