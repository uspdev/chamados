<button type="button" class="btn btn-sm btn-light text-primary" onclick="json_modal_form()">
    <i class="fas fa-copy"></i> Editar Json
</button>

<!-- Modal -->
<div class="modal fade" id="json-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalShowJson" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalShowJson">Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="list_table_div_form">
                    {!! Form::open( ['url'=>$data->url.'/'.$fila->id.'/template_json', 'id' => 'jsonForm']) !!}
                    @method('POST')
                    @csrf

                    <div class="form-group row">
                        {{ Form::label('template', 'Json', ['class' => 'col-form-label col-sm-2']) }}
                        <div class="col-sm-10">
                            @if($fila->template == null)
                            {{ Form::textarea('template', $fila->template,['id' => 'template', 'class' => 'form-control', 'rows' => '15'] ) }}
                            @else
                            {{ Form::textarea('template', json_encode(json_decode($fila->template), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),['id' => 'template', 'class' => 'form-control', 'rows' => '15'] ) }}
                            @endif
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" onclick="validaJson()" class="btn btn-primary">Salvar</button>
                    </div>
                    {!! Form::close(); !!}
                </div>

            </div>
        </div>
        {{-- <div class="modal-footer"></div> --}}
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        var jsonForm = $('#json-modal-form');
        json_modal_form = function() {
            jsonForm.modal();
        }

    });

    function validaJson() {

        var json = $('#template').val();

        if (json != '') {
            try {
                obj = JSON.parse(json);
            } catch (e) {
                alert('Erro: Json mal formatado!');
                alert(e);
                return
            }
        }
        document.getElementById("jsonForm").submit();
    }
</script>
@endsection