<div class="btn-group enable-disable-btn">
    <button class="btn btn-sm btn-secondary disabled" name="disable">
        Em elaboração
    </button>
    <button class="btn btn-sm btn-success active" name="enable">
        Em produção
    </button>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(function() {

        $('.enable-disable-btn').find('[name=disable]').click(function() {
            if ($(this).hasClass('active')) {
                $(this).blur()
                return false
            }
            if (confirm('Deseja mudar para EM ELABORAÇÃO?')) {
                $(this).removeClass('disabled').removeClass('btn-secondary').addClass('btn-danger').addClass('active').blur();
                $(this).parent().find('[name=enable]').removeClass('active').removeClass('btn-success').addClass('btn-secondary').addClass('disabled')
            }

        })

        $('.enable-disable-btn').find('[name=enable]').click(function() {
            if ($(this).hasClass('active')) {
                $(this).blur()
                return false
            }
            if (confirm('Deseja mudar para EM PRODUÇÃO?')) {
                $(this).removeClass('disabled').removeClass('btn-secondary').addClass('btn-success').addClass('active').blur();
                $(this).parent().find('[name=disable]').removeClass('active').removeClass('btn-danger').addClass('btn-secondary').addClass('disabled')
            }
        })

    });

</script>

@endsection
