<div class="input-group col-6 col-sm-4 col-md-2">
    <input class="form-control form-control-sm" type="text" id="dt-search" value="{{ $dtSearch ?? '' }}" placeholder="Filtrar...">
    <div class="input-group-append">
        <button class="btn btn-sm btn-outline-secondary" id="dt-search-clear">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        $('#dt-search').focus();

        // vamos filtrar à medida que digita
        $('#dt-search').keyup(function() {
            oTable.search($(this).val()).draw()
            $('.datatable-counter').html(oTable.page.info().recordsDisplay)
        })

        // vamos limpar o filtro de busca
        $('#dt-search-clear').on('click', function() {
            $('#dt-search').val('').trigger('keyup');
            $('#dt-search').focus();
        })

        $('#dt-search').trigger('keyup');

    })
</script>
@endsection