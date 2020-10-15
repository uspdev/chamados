<?php #dd($data); ?>

{{-- enviar $row, $model ['name', 'label'] --}}

<table class="table table-striped table-sm table-hover datatable-nopagination">
    <thead>
        <tr>
            @foreach ($fields as $col)
            <th>{{ $col['label'] ?? $col['name'] }}</th>
            @endforeach

            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
        <tr>

            @foreach ($fields as $col)
            <td>@include('common/list-table-col-value')</td>
            @endforeach

            <td style="width: 40px;">
                <div class="row">
                    <div class="mr-2 form-inline">
                        @foreach($btns as $btn)
                        @include($btn)
                        @endforeach
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
            dom: 'ti'
            , "paging": false
        });

    })

</script>
@endsection
