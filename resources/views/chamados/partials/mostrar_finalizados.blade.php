<div class="form-group form-check">
    <label class="form-check-label">Mostrar finalizados</label>
    <input type="checkbox" id="mostrar_finalizados" {{ session('finalizado') ? 'checked':'' }} class="form-check-input ml-1">
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        $('#mostrar_finalizados').change(function() {
            if (this.checked) {
                window.location.href = 'chamados?finalizado=1'
            } else {
                window.location.href = 'chamados?finalizado=0'
            }
        })

    })

</script>
@endsection
