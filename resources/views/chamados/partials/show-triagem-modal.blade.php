<button type="button" class="btn btn-sm btn-light text-primary" onclick="add_modal_form()">
    <i class="fas fa-edit"></i> Editar
</button>
<!-- Modal -->
<div class="modal fade" id="common-modal-form" data-backdrop="static" tabindex="-1" aria-labelledby="modalPortariaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPortariaLabel">{{ $modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="list_table_div_form">

                    {!! Form::open(['url'=>'triagem/'.$chamado->id]) !!}
                    @method('post')
                    @csrf

                    <div class="col-sm form-group">
                        <label for="atribuido_para"><b>Atribuir para:</b></label>
                        <select name="atribuido_para" class="form-control">
                            <option value="" selected="">Escolher</option>

                            @foreach($chamado->fila->users as $atendente)
                            @if(old('atribuido_para') == '' and isset($chamado->atribuido_para))
                            <option value="{{ $atendente->codpes }}" {{ ( $chamado->atribuido_para == $atendente->codpes) ? 'selected' : ''}}>
                                {{ $atendente->name }}
                            </option>
                            @else
                            <option value="{{ $atendente->codpes }}" {{ (old('atribuido_para') == $atendente->codpes) ? 'selected' : ''}}>
                                {{ $atendente->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-sm form-group">
                        <label for="complexidade"><b>Complexidade:</b></label>
                        <select name="complexidade" class="form-control">
                            <option value="" selected="">Escolher</option>
                            @foreach($complexidades as $complexidade)
                            @if(old('complexidade') == '' and isset($chamado->complexidade))
                            <option value="{{ $complexidade }}" {{ ( $chamado->complexidade == $complexidade) ? 'selected' : ''}}>
                                {{ $complexidade }}
                            </option>
                            @else
                            <option value="{{ $complexidade }}" {{ (old('complexidade') == $complexidade) ? 'selected' : ''}}>
                                {{ $complexidade }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
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

        var pessoasForm = $('#common-modal-form');

        add_modal_form = function() {
            pessoasForm.modal();
        }

        pessoasForm.find(':input[name=codpes]').select2({
            ajax: {
                url: 'search/partenome'
                , dataType: 'json'
            }
            , dropdownParent: pessoasForm
            , minimumInputLength: 4
            , theme: 'bootstrap4'
            , wdth: 'resolve'
            , language: 'pt_br'
        })

    })

</script>
@endsection
