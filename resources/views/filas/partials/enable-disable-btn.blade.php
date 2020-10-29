<input type="hidden" name="estado_fila" value="{{ $data->row->estado }}">

<div class="btn-group enable-disable-btn">
    <button class="btn btn-sm" name="elaboracao">
        Em elaboração
    </button>
    <button class="btn btn-sm" name="producao">
        Em produção
    </button>
    <button class="btn btn-sm" name="desativada">
        Desativada
    </button>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {
        var estado_fila = $('.enable-disable-btn').parent().find('[name=estado_fila]').val();
        mudaBotaoFila(estado_fila);
    })

    function mudaBotaoFila(estado_fila) {

        if (estado_fila == 'Em produção') {
            botao = $('.enable-disable-btn').find('[name=producao]');
            botao.removeClass('disabled').removeClass('btn-secondary').addClass('btn-success').addClass('active').blur();
            botao.parent().find('[name=desativada]').removeClass('active').removeClass('btn-danger').addClass('btn-secondary').addClass('disabled');
            botao.parent().find('[name=elaboracao]').removeClass('active').removeClass('btn-warning').addClass('btn-secondary').addClass('disabled');
        
        } else if (estado_fila == 'Em elaboração') {
            botao = $('.enable-disable-btn').find('[name=elaboracao]');
            botao.removeClass('disabled').removeClass('btn-secondary').addClass('btn-warning').addClass('active').blur();
            botao.parent().find('[name=producao]').removeClass('active').removeClass('btn-success').addClass('btn-secondary').addClass('disabled');
            botao.parent().find('[name=desativada]').removeClass('active').removeClass('btn-danger').addClass('btn-secondary').addClass('disabled');
        
        } else { //'Desativada'
            botao = $('.enable-disable-btn').find('[name=desativada]');
            botao.removeClass('disabled').removeClass('btn-secondary').addClass('btn-danger').addClass('active').blur();
            botao.parent().find('[name=producao]').removeClass('active').removeClass('btn-success').addClass('btn-secondary').addClass('disabled');
            botao.parent().find('[name=elaboracao]').removeClass('active').removeClass('btn-warning').addClass('btn-secondary').addClass('disabled');
        }
    }

    $(function() {

        $('.enable-disable-btn').find('[name=elaboracao]').click(function() {
            if ($(this).hasClass('active')) {
                $(this).blur()
                return false
            }
            if (confirm('Deseja mudar para EM ELABORAÇÂO?')) {
                $(this).removeClass('disabled').removeClass('btn-secondary').addClass('btn-warning').addClass('active').blur();
                $(this).parent().find('[name=desativada]').removeClass('active').removeClass('btn-danger').addClass('btn-secondary').addClass('disabled');
                $(this).parent().find('[name=producao]').removeClass('active').removeClass('btn-success').addClass('btn-secondary').addClass('disabled');
            }
        })

        $('.enable-disable-btn').find('[name=producao]').click(function() {
            if ($(this).hasClass('active')) {
                $(this).blur()
                return false
            }
            if (confirm('Deseja mudar para EM PRODUÇÃO?')) {
                $(this).removeClass('disabled').removeClass('btn-secondary').addClass('btn-success').addClass('active').blur();
                $(this).parent().find('[name=desativada]').removeClass('active').removeClass('btn-danger').addClass('btn-secondary').addClass('disabled');
                $(this).parent().find('[name=elaboracao]').removeClass('active').removeClass('btn-warning').addClass('btn-secondary').addClass('disabled');
            }
        })

        $('.enable-disable-btn').find('[name=desativada]').click(function() {
            if ($(this).hasClass('active')) {
                $(this).blur()
                return false
            }
            if (confirm('Deseja mudar para DESATIVADA?')) {
                $(this).removeClass('disabled').removeClass('btn-secondary').addClass('btn-danger').addClass('active').blur();
                $(this).parent().find('[name=producao]').removeClass('active').removeClass('btn-success').addClass('btn-secondary').addClass('disabled');
                $(this).parent().find('[name=elaboracao]').removeClass('active').removeClass('btn-warning').addClass('btn-secondary').addClass('disabled');
            }
        })

    });
</script>
@endsection