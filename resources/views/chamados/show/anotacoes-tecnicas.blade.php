<form id="anotacoes_form" name="anotacoes_form" method="POST" action="chamados/{{ $chamado->id }}">
  @csrf
  @method('PUT')
  Anotações técnicas <span class="status"></span>
  <textarea class="form-control" rows="5" name="anotacoes" {{ $chamado->status == 'Fechado' ? 'disabled' : '' }}
    placeholder="Esse conteúdo é visível somente para o atendente.">{{ $chamado->anotacoes }}</textarea>
</form>

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {

      // {{-- https://stackoverflow.com/questions/931252/ajax-autosave-functionality --}}
      var $status = $('#card-atendente').find('.status'),
        $anotacoes = $('#anotacoes_form').find('textarea'),
        timeoutId, timeout = 1500 // aguarda 1,5s sem atividade para salvar

      $anotacoes.keyup(function() { // keypress or keyup to detect backspaces
        $status.show()
        $status.attr('class', 'badge badge-warning text-light ml-3')
          .html('<i class="fas fa-sync-alt fa-spin"></i> Pendente');

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
                $status.attr('class', 'badge badge-secondary ml-3')
                  .html('<i class="fas fa-save"></i> Salvo');
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
