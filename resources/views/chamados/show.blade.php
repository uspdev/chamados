@extends('master')

@section('title', '#'.$chamado->nro.'/'.$chamado->created_at->year)

@section('styles')
@parent
<style>
    #card-principal {
        border: 1px solid blue;
    }

    .bg-principal {
        background-color: LightBlue;
        border-top: 3px solid blue;
    }

</style>
@endsection

@section('content')
@parent

<div class="card bg-light mb-3" id="card-principal">
    <div class="card-header bg-principal">
        <span class="text-muted">Chamado no.</span>
        {{ $chamado->nro }}/{{ Carbon\Carbon::parse($chamado->created_at)->format('Y') }}
        <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
        @include('chamados.partials.status')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-8">
                        <span class="ml-2 float-right">
                            <button class="btn btn-light text-primary" data-toggle="tooltip" title="Editar" onclick="mostraModal()">
                                <i class="far fa-edit"></i>
                            </button>
                        </span>
                        {{-- Informações principais --}}
                        @include('chamados.show.main')
                    </div>
                    <div class="col-md-4">
                        {{-- formulario--}}
                        @includewhen(!empty($template),'chamados.show.dados-formulario')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        @includewhen(Gate::check('perfilAtendente') || Gate::check('perfilAdmin'),'chamados.show.card-atendente')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        @include('chamados.show.card-comentarios-user')
                    </div>
                    <div class="col-md-4">
                        @include('chamados.show.card-arquivos')
                        @include('chamados.show.card-pessoas')
                        @include('chamados.show.card-vinculados')
                    </div>
                    <div class="col-md-4">
                        @include('chamados.show.card-comentarios-system')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="modal fade show" id="chamadoModal" data-backdrop="static" tabindex="-1" aria-labelledby="modalChamadoEdit" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalChamadoEdit">Chamado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="list_table_div_form">
                    <form method="POST" role="form" action="{{ route('chamados.update', $chamado ) }}">
                        @csrf
                        {{ method_field('patch') }}
                        <div class="form-group">
                            <label class="control-label" for="assunto">Assunto</label>
                            <input class="form-control" id="assunto" name="assunto" value="{{ $chamado->assunto ?? old('assunto') }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="descricao">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4">{{ $chamado->descricao ?? old('descricao') }}</textarea>
                        </div>
                        @foreach($form as $input)
                        <div class="form-group">
                            @foreach($input as $element)
                            {{ $element }}
                            @endforeach
                        </div>
                        @endforeach
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascripts_bottom')
@parent
<script type="text/javascript">
    function mostraModal() {
        $('#chamadoModal').modal('show');
    }

</script>
@stop
