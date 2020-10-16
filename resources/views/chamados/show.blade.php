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
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">

                {{-- Informações principais --}}
                <span class="text-muted">Assunto:</span> {{ $chamado->assunto }}<br>
                <br>
                @foreach($template as $field => $val)
                <span class="text-muted">{{ $val->label }}:</span>
                <span>{{ $extras->$field ?? '' }}<br>
                    @endforeach
                    <span class="text-muted">Descrição:</span> {{ $chamado->descricao ?? '' }}<br>

            </div>
            <div class="col-md-4">

                {{-- Painel direito --}}
                <span class="text-muted">Criado por:</span> {{ $autor->name}} @include('chamados.partials.user-detail', ['user'=>$autor])<br>
                <span class="text-muted">Criado em:</span> {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}<br>

                @if(!is_null($chamado->fechado_em))
                <b>fechado em</b>: {{ Carbon\Carbon::parse($chamado->fechado_em)->format('d/m/Y H:i') }}<br>
                @endif

                <span class="text-muted">Estado:</span> @include('chamados.partials.status')

                @can('admin')
                |
                @include('chamados.partials.show-triagem-modal', ['modalTitle'=>'Triagem', 'url'=>'ok'])
                @endcan
                <br>
                <div class="ml-2">

                    @if($chamado->status == 'Atribuído')
                    <span class="text-muted">Atribuído para</span>:
                    {{ $atendente->name }}<br>

                    <span class="text-muted">Complexidade</span>: {{ $chamado->complexidade }}<br>

                    <span class="text-muted">Por</span>:
                    {{ $atribuidor->name }}
                    <span class="text-muted">em</span> {{ $atribuidor->created_at }}
                    <pre>PEGAR DATA DO USER_CHAMADO</pre><br>
                    @endif
                    @if($chamado->status == 'Triagem')
                    Não atribuído
                    @endif

                </div>

                @include('chamados.partials.show-vinculados')


            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        @include('chamados.partials.comentarios')
    </div>
    <div class="col-md-6">
        @include('chamados.partials.file-upload')
    </div>
</div>


@stop
