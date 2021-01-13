@extends('master')

@section('title', 'Novo Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
    @parent
@stop

@section('content')
    @parent

    <div class="card bg-light mb-3">
        <div class="card-header h5">
            <span class="text-muted">Novo chamado para</span> ({{ $fila->setor->sigla }}) {{ $fila->nome }}
        </div>
        <div class="card-body">
            <form method="POST" role="form" action="{{ route('chamados.store', $fila->id) }}">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="autor" class="control-label">Por</label>
                            <input class="form-control" type="text" name="autor" value="{{ \Auth::user()->name }}" disabled>
                            <br>
                            <label for="assunto" class="control-label">Assunto</label>
                            <input class="form-control" type="text" name="assunto" value="{{ old('assunto') }}">
                            <br>
                            <label for="descricao" class="control-label">Descrição</label>
                            <textarea class="form-control" name="descricao">{{ old('descricao') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            @foreach ($form as $input)
                                @foreach ($input as $element)
                                    {{ $element }}
                                @endforeach
                                <br>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="form-group card-header">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </div>
            </form>
        </div>
    </div>
@endsection
