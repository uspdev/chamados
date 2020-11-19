@can('admin')
<?php
//dd($chamado);
?>
@endcan

<div class="modal fade" id="chamadoModal" data-backdrop="static" tabindex="-1" aria-labelledby="modalChamadoEdit" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChamadoEdit">Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="assunto">Assunto</label>
                    <input class="form-control" id="assunto" name="assunto" value="{{ $chamado->assunto ?? old('assunto') }}">
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4">{{ $chamado->descricao ?? old('descricao') }}</textarea>
                </div>

                @foreach($form as $input)
                <div class="form-group">
                    @foreach($input as $element)
                    {{ $element }}
                    @endforeach
                </div>
                @endforeach

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</div>