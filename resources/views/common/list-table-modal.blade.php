<!-- Modal -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {!! Form::open( ['url'=>$data->url]) !!}
        @method('POST')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">Portaria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('id') }}

                @foreach ($data->fields as $col)
                <div class="form-group row">
                    {{ Form::label($col['name'], $col['label'] ?? $col['name'], ['class' => 'col-form-label col-sm-2']) }}
                    <div class="col-sm-10">
                        {{ Form::text($col['name'],null,['class'=>'form-control']) }}
                    </div>
                </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </div>
        {!! Form::close(); !!}

    </div>
</div>
