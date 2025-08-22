  @if ($chamado->status == 'Fechado')
    <button type="button" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#comentarioModal">
      <i class="fas fa-lock-open"></i> Reabrir chamado
    </button>
  @else
    <button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#comentarioModal">
      <i class="far fa-comment-dots"></i> Adicionar
    </button>
  @endif

  <!-- Modal -->
  <div class="modal fade" id="comentarioModal" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Adicionar comentário</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          {{ html()->form('POST', 'comentarios/' . $chamado->id)->open() }}
          @csrf

          <div class="form-group">
            <textarea class="form-control" id="comentario" name="comentario" rows="7" required></textarea>
          </div>

          @if ($chamado->status != 'Fechado')
            <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="fechar_check">
              <label class="form-check-label" for="fechar_check">Fechar o chamado</label>
            </div>
          @endif

          <div class="form-group">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            @if ($chamado->status != 'Fechado')
              <button id="enviar_btn" type="submit" class="btn btn-primary" value="">Enviar</button>
              <button id="enviar_fechar_btn" type="submit" class="btn btn-danger d-none" name="status" value="Fechado">
                <i class="fas fa-lock"></i> Enviar e fechar
              </button>
            @else
              <button type="submit" class="btn btn-danger float-right" name="status" value="Reabrir">
                <i class="fas fa-lock-open"></i> Enviar e reabrir
              </button>
            @endif
          </div>
          {{ html()->form()->close() }}

        </div>
      </div>
    </div>
  </div>

  @section('javascripts_bottom')
    @parent
    <script>
      $(document).ready(function() {
        $('#comentarioModal').on('shown.bs.modal', function() {
          $('#comentario').trigger('focus')
        })

        // alternar entre os botões de enviar e fechar
        $('#fechar_check').change(function() {
          if (this.checked) {
            $('#enviar_btn').addClass('d-none');
            $('#enviar_fechar_btn').removeClass('d-none');
          } else {
            $('#enviar_btn').removeClass('d-none');
            $('#enviar_fechar_btn').addClass('d-none');
          }
        });
      })
    </script>
  @endsection
