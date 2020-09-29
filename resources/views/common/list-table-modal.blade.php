<!-- Modal -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        {!! Form::open( ['url'=>$data->url]) !!}
        @method('POST')
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">{{ $data->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ Form::hidden('id') }}

                @foreach ($data->fields as $col)
                @if (empty($col['type']))
                    @include('common/list-table-modal-text')

                @elseif ($col['type'] == 'select')
                    @include('common/list-table-modal-select')

                @endif
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
