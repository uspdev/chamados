<button type="button" class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#common-modal-form">
    <i class="fas fa-plus"></i> Atender
</button>
<!-- Modal -->
<div class="modal fade" id="common-modal-form" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modalTitle }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">
                    {!! Form::open(['url'=>'triagem/'.$chamado->id]) !!}
                    @method('post')
                    @csrf

                    <div class="form-group row mb-2">
                        <label class="col-form-label col-sm-2" for="name"><b>Atendente:</b></label>
                        <div class="col-sm-10">
                            <input type="hidden" name="codpes" value="{{ \Auth::user()->codpes }}">
                            <input class="form-control" type="text" name="name" value="{{ \Auth::user()->name }}" readonly style="width: 100%;">
                        </div>
                    </div>

                    @include('chamados.show.mudar-status')
                    @include('chamados.show.mudar-complexidade')

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