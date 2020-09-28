
{{-- 
Copiar esse botão de adicionar para onde quiser
<button type="button" class="btn btn-sm btn-success" onclick="add_form()">
    <i class="fas fa-plus"></i> Novo
</button> 
--}}

@if (!$data)
É necessário enviar a variável $data para que este componente funcione.
@else

{{-- incluindo o modal com form  --}}
@include('common.list-table-modal')

<table class="table table-striped table-sm datatable-nopagination">
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
            <td>{{ $row->{$col['name']} }}</td>
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

        $('.datatable-nopagination').DataTable({dom: 'ti'});

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

    })

</script>
@endsection

@endif
