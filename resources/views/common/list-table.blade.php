@if (!$data)
<div>É necessário enviar a variável $data para que este componente funcione.</div>
@else

<?php #dd($data); ?>

<table class="table table-striped table-sm table-hover datatable-nopagination display responsive" style="width:100%">
    <thead>
        <tr>
            @if ($data->showId ?? true)
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
            @if ($data->showId ?? true)
            <td>{{ $row->id }}</td>
            @endif

            @foreach ($data->fields as $col)
            <td>@include('common/list-table-col-value')</td>
            @endforeach

            <td style="width: 40px;">
                <div class="row">
                    <div class="mr-2">
                        @includewhen($data->viewBtn ?? false,'common.list-table-btn-view')
                        @includewhen($data->editBtn ?? true,'common.list-table-btn-edit')
                    </div>
                    <div>
                        @includewhen($data->delete ?? true,'common.list-table-btn-delete')
                    </div>
                </div>
            </td>

        </tr>
        @endforeach
    </tbody>
</table>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.datatable-nopagination').DataTable({
            dom: 'ti',
            "paging": false
        });

    })

</script>
@endsection

@endif
