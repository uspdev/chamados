@extends('master')

<?php #dd($data) ?>

@section('content')
@parent

<style>
    td.details-control {
        text-align: center;
        color: forestgreen;
        cursor: pointer;
    }

    tr.shown td.details-control {
        text-align: center;
        color: red;
    }

</style>

@if ($data->modal ?? false)
{{-- incluindo o modal com form  --}}
@include('common.list-table-modal')
@endif

<div class="row">
    <div class="col-md-12 form-inline">

        <span class="h4 mt-2">Setores</span>

        @include('partials.datatable-filter-box')

        @if ($data->modal ?? false)
        @include('common.list-table-modal-btn-create')
        @else
        {{-- @include('common.list-table-btn-create') --}}
        @endif

    </div>
</div>



<?php #dd($data); ?>

<table class="table table-striped table-sm table-hover datatable-nopagination">
    <thead>
        <tr>
            <th></th>
            <th>Sigla</th>
            <th>Nome</th>
            <th>Pai</th>
            <th>Gerente</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

@endsection

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {
        $(window).on("load", function() {
            if (location.hash) location.href = location.hash;
        })

        /* Formatting function for row details - modify as you need */
        function format(d) {
            // `d` is the original data object for the row
            return '<div> Gerente: (' +
                d.users[0].codpes + ')' + d.users[0].name +
                '</div>';
        }

        oTable = $('.datatable-nopagination').DataTable({
            dom: 't'
            , "paging": false
            , "ajax": "setores"
            , "columns": [{
                        "className": 'details-control'
                        , "orderable": false
                        , "data": null
                        , "defaultContent": ''
                        , "render": function() {
                            return '<i class="fa fa-plus-square" aria-hidden="true"></i>';
                        }
                        , width: "15px"
                    }
                    , {
                        "data": "sigla"
                    }
                    , {
                        "data": "nome"
                    }
                    , {
                        "data": "setor.sigla"
                        , "defaultContent": ""
                    }
                    , {
                        "data": "users"
                        , "defaultContent": ''
                        , 'render': function(d) {
                            if (d[0] != null)
                                return d[0].codpes + ' - ' + d[0].name + '<br> segundo nome'
                        }
                    }
                ]

            , "order": [
                [1, 'asc']
            ]
        });

        // Add event listener for opening and closing details
        $('.datatable-nopagination tbody').on('click', 'td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = oTable.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.find('svg').attr('data-icon', 'plus-square');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.find('svg').attr('data-icon', 'minus-square');
            }
        });

    })

</script>
@endsection
