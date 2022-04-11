<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#triagem-modal-form">
  <i class="fas fa-share-square"></i> Atribuir
</button>
<!-- Modal -->
<div class="modal fade" id="triagem-modal-form" data-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Atribuir atendimento para</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form method="post" action="chamados/{{ $chamado->id }}/triagem">
          @csrf
          <div class="form-group row">
            <label class="col-form-label col-sm-2" for="codpes-triagem"><b>Atendente</b></label>
            <div class="col-sm-10">
              <select name="codpes" class="form-control" id="codpes-triagem" required>
                <option value="">Escolher ..</option>
                @foreach ($chamado->fila->users as $atend)
                  <option value="{{ $atend->codpes }}" {{ old('codpes') == $atend->codpes ? 'selected' : '' }}>
                    {{ $atend->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="text-right">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            <button type="submit" class="btn btn-primary">Salvar</button>
          </div>
        </form>
      </div>

    </div>
    {{-- <div class="modal-footer"></div> --}}
  </div>
</div>
