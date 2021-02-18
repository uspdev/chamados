<a name="card_atendente"></a>
<div class="card bg-light border-warning mb-3" id="card-atendente">
    <div class="card-header bg-warning">
        <div class="form-inline">
            Atendente
            <span title="Esta região é visível somente para o atendente, para que possa fazer anotações técnicas" class="ajuda mx-2" data-toggle="tooltip"><i class="fas fa-question-circle"></i></span>
            <span class="status">
                <span class="badge badge-light mr-2"></span>
            </span>            
                @if($chamado->fila->config->triagem)
                @includeWhen($chamado->status != 'Fechado', 'chamados.partials.show-triagem-modal', ['modalTitle'=>'Triagem', 'url'=>'ok'])
                @else
                @includeWhen($chamado->status != 'Fechado', 'chamados.partials.show-atender-modal', ['modalTitle'=>'Atender', 'url'=>'ok'])
                @endif
            
        </div>
        
    </div>
    <div class="card-body">
        <div class="row">           
            
            <div class="col-md-8">
                <form id="anotacoes_form" name="anotacoes_form" method="POST" action="chamados/{{$chamado->id}}">
                    @csrf
                    @method('PUT')
                    <b>Anotações:</b>
                    <textarea class="form-control" rows="5" name="anotacoes">{{ $chamado->anotacoes }}</textarea>
                </form>
            </div>
            <div class="col-md-4">
                @includewhen(Gate::check('atendente'),'chamados.show.mudar-status')
                @includewhen(Gate::check('atendente'),'chamados.show.dados-formulario-atendente')
            </div>
        </div>
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(function() {
        // {{-- https://stackoverflow.com/questions/931252/ajax-autosave-functionality --}}
        var $status = $('#card-atendente').find('.status'),
            $anotacoes = $('#anotacoes_form').find('textarea'),
            timeoutId, timeout = 1500

        $anotacoes.keyup(function() { // keypress or keyup to detect backspaces
            $status.show()
            $status.attr('class', 'badge badge-warning text-light').html('<i class="fas fa-sync-alt fa-spin"></i> Pendente');

            // If a timer was already started, clear it.
            if (timeoutId) clearTimeout(timeoutId)

            // Set timer that will save comment when it fires.
            timeoutId = setTimeout(function() {
                $form = $('#anotacoes_form')
                // Make ajax call to save data.
                $.ajax({
                    url: $form.attr('action'),
                    type: 'put',
                    dataType: 'json',
                    data: $form.serialize(),
                    success: function(data) {
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

        var $estado = $('#status_form').find('#estado'),
            $form_atendente = $("input, textarea, select",'#form_atendente');
                    
        $estado.change(function() {
            $('#status_form').find('#btn_salvar_status').show();
        });
        
        $form_atendente.change(function() {
            $('#form_atendente').find('#btn_salvar_form_atendente').show();
        });   
    });
</script>
@stop