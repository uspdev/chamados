<div class="card bg-light mb-3" id="anotacoes">
    <div class="card-header bg-warning">
        Anotações
        <span title="Esta região é visível somente para o atendente, para que possa fazer anotações técnicas" class="ajuda" data-toggle="tooltip"><i class="fas fa-question-circle"></i></span>
        <span class="status">
            <span class="badge badge-light"></span>
        </span>
    </div>
    <div class="card-body">
        <form id="anotacoes_form" name="anotacoes_form" method="POST" action="chamados/{{$chamado->id}}">
            @csrf
            @method('PUT')
            <textarea class="form-control" rows="10" name="anotacoes">{{ $chamado->anotacoes }}</textarea>
        </form>
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(function() {

        // {{-- https://stackoverflow.com/questions/931252/ajax-autosave-functionality --}}
        var $status = $('#anotacoes').find('.status')
            , $anotacoes = $('#anotacoes').find('textarea')
            , timeoutId
            , timeout = 1500

        $anotacoes.keyup(function() { // keypress or keyup to detect backspaces
            $status.show()
            $status.attr('class', 'badge badge-warning text-light').html('<i class="fas fa-sync-alt fa-spin"></i> Pendente');

            // If a timer was already started, clear it.
            if (timeoutId) clearTimeout(timeoutId)

            // Set timer that will save comment when it fires.
            timeoutId = setTimeout(function() {
                $form = $('#anotacoes').find('form')
                // Make ajax call to save data.
                $.ajax({
                    url: $form.attr('action')
                    , type: 'put'
                    , dataType: 'json'
                    , data: $form.serialize()
                    , success: function(data) {
                        console.log($('#anotacoes_form').find('textarea').val())
                        if (data.message == 'success') {
                            $status.attr('class', 'badge badge-secondary').html('<i class="fas fa-save"></i> Salvo');
                            // vamos ocultar depois de 5s
                            setTimeout(function() {
                                $status.hide('blind', {}, 500)
                            }, 5000)
                        } else {
                            alert(data.message)
                        }
                    }
                });
            }, timeout);
        });
    });

</script>
@stop
