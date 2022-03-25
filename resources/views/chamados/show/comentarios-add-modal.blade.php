<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#comentarioModal">
  <i class="fas fa-plus"></i> Adicionar
</button>

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

        {!! Form::open(['url' => 'comentarios/' . $chamado->id]) !!}
        @method('post')
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
              Enviar e fechar
            </button>
          @else
            <button type="submit" class="btn btn-danger float-right" name="status" value="Reabrir">
              Enviar e reabrir
            </button>
          @endif
        </div>
        {!! Form::close() !!}

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
