<button type="button" class="btn btn-sm btn-success" onclick="add_form()">
    <i class="fas fa-plus"></i> Novo
</button>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        add_form = function() {
            // limpando os inputs
            var inputs = $("#modalForm :input").not(":input[type=button], :input[type=submit], :input[type=reset], input[name^='_']");
            inputs.each(function() {
                $(this).val('');
            });
            $("#modalForm").modal();
        }

    })

</script>
@endsection
