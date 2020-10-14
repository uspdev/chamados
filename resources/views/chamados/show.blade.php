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
                <span class="text-muted">Assunto:</span> {{ $chamado->chamado }}<br>
                <br>
                @foreach($template as $field => $val)
                <span class="text-muted">{{ $val->label }}:</span>
                <span>{{ $extras->$field ?? '' }}<br>
                @endforeach
                <span class="text-muted">Descrição:</span> {{ $chamado->descricao ?? '' }}<br>

            </div>
            <div class="col-md-4">

                {{-- Painel direito --}}
                <span class="text-muted">Criado por:</span> {{ $chamado->user->name}} @include('chamados.partials.user-detail', ['user'=>$chamado->user])<br>
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
                    {{ App\Models\User::getByCodpes($chamado->atribuido_para)['name'] }}<br>

                    <span class="text-muted">Complexidade</span>: {{ $chamado->complexidade }}<br>

                    <span class="text-muted">Por</span>:
                    {{ App\Models\User::getByCodpes($chamado->triagem_por)['name'] }}
                    <span class="text-muted">em</span> {{ Carbon\Carbon::parse($chamado->atribuido_em)->format('d/m/Y H:i') }}<br>
                    @endif
                    @if($chamado->status == 'Triagem')
                    Não atribuído
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<div class="card bg-light mb-3">
    <div class="card-header h5">
        Comentários
        <span class="badge badge-pill badge-primary">{{ $chamado->comentarios->count() }}</span>
        <a class="btn btn-outline-primary btn-sm" href="chamados/{{$chamado->id}}/edit"> <i class="fas fa-plus"></i> Adicionar</a>

    </div>
    <div class="card-body">

        @forelse ($chamado->comentarios->sortByDesc('created_at') as $comentario)

        <div class="">
            {{ $comentario->user->name }} - {{ Carbon\Carbon::parse($comentario->created_at)->format('d/m/Y H:i') }}
        </div>
        <div class="ml-2">
            <p class="card-text">{!! $comentario->comentario !!}</p>
        </div>
        <hr />

        @empty
        Não há comentários
        @endforelse

    </div>
</div>
@can('update',$chamado)
<form method="POST" role="form" action="comentarios/{{$chamado->id}}">
    @csrf

    <div class="form-group">
        <label for="comentario"><b>Novo comentário:</b></label>
        <textarea class="form-control" id="comentario" name="comentario" rows="7"></textarea>
    </div>

    @if($chamado->status == 'Triagem' or $chamado->status == 'Atribuído')
    <div class="form-group">
        <button type="submit" class="btn btn-primary" value="">Enviar</button>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-danger" name="status" value="Fechado">Enviar e fechar chamado</button>
    </div>
    @else
    <div class="form-group">
        <button type="submit" class="btn btn-danger" name="status" value="Triagem">Enviar e reabrir chamado</button>
    </div>
    @endif
</form>
@endcan

@stop
