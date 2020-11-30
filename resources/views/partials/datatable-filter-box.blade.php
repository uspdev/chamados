<div class="input-group col-6 col-sm-4 col-md-2">
    <input class="form-control form-control-sm" type="text" id="dt-search" placeholder="Filtrar...">
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

        $('#dt-search').keyup(function() {
            {{$otable ?? 'oTable'}}.search($(this).val()).draw();
        })

        $('#dt-search-clear').on('click', function() {
            $('#dt-search').val('').trigger('keyup');
            $('#dt-search').focus();
        })

    })
</script>
@endsection