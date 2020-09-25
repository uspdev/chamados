@if (!$data)
    É necessário enviar a variável $data para que este componente funcione.
@else
    <table class="table table-striped table-sm datatable">
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
                                <button class="btn btn-light text-primary py-0 px-0" data-toggle="tooltip"
                                    title="Editar" onclick="edit_form('id')">
                                    <i class="far fa-edit"></i>
                                </button>
                            </div>
                            <div class="col">
                                {{-- Form de deletar --}}
                                <form method="POST" action="/setores/{{ $row->id }}" class="form-inline pull-left">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-light text-danger py-0 px-0"
                                        data-toggle="tooltip" title="Remover"
                                        onclick="return confirm('Tem certeza que deseja deletar?')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </td>
            @endforeach
        </tbody>
    </table>
@endif
