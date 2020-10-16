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