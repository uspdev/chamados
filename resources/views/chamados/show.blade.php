@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
@parent
<script>
    //CKEDITOR.replace('comentario');

</script>
@stop

@section('content')
@parent

<div class="card bg-light mb-3">

    <div class="card-header h5">

        <span class="text-muted">Chamado no.</span>
        {{ $chamado->id }}/{{ Carbon\Carbon::parse($chamado->created_at)->format('Y') }}
        <span class="text-muted">para</span> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}
        @include('chamados.partials.status')
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">

                {{-- Informações principais --}}
                <span class="text-muted">Criado por:</span> {{ $autor->name}} @include('chamados.partials.user-detail', ['user'=>$autor])<br>
                <span class="text-muted">Criado em:</span> {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}<br>

                @if(!is_null($chamado->fechado_em))
                <span class="text-muted">Fechado em</span>: {{ Carbon\Carbon::parse($chamado->fechado_em)->format('d/m/Y H:i') }}<br>
                @endif

                <span class="text-muted">Assunto:</span> {{ $chamado->assunto }}<br>
                <span class="text-muted">Descrição:</span><br>
                <span class="ml-2">{{ $chamado->descricao ?? '' }}</span><br>

            </div>
            <div class="col-md-4">
                {{-- Painel meio --}}

                <span class="font-weight-bold">Formulário</span><br>
                @foreach($template as $field => $val)
                <span class="text-muted">{{ $val->label }}:</span>
                <span>{{ $extras->$field ?? '' }}</span><br>
                @endforeach
            </div>

            <div class="col-md-4">

                {{-- Painel direito --}}


                <div class="">
                    <span class="font-weight-bold">Atendente</span>
                    @can('admin')
                    |
                    @include('chamados.partials.show-triagem-modal', ['modalTitle'=>'Triagem', 'url'=>'ok'])
                    @endcan<br>

                    <div class="ml-2">
                        @if($atendentes->count())
                            @foreach($atendentes as $atendente)
                            {{ $atendente->name }} @include('chamados.partials.atribuido-detail', ['user'=>$atendente])<br>
                            @endforeach
                            <span class="text-muted">Complexidade</span>: {{ $chamado->complexidade }}<br>
                        @else
                            Não atribuído
                        @endif
                    </div>
                </div>

                <div>
                    <br>
                    <b>Observação</b> (visível somente para o técnico)<br>
                    <textarea class="form-control">{{ $chamado->observacao_tecnica }}</textarea>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        @include('chamados.partials.comentarios-card')
    </div>
    <div class="col-md-4">
        @include('chamados.partials.show-vinculados')
        @include('chamados.partials.file-upload-card')

    </div>
</div>


@stop
