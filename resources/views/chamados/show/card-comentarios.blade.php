<div class="card bg-light mb-3">
    <div class="card-header h5">
        Comentários
        <span class="badge badge-pill badge-primary">{{ $chamado->comentarios->count() }}</span>
        @can('update',$chamado)
        @include('chamados.show.comentarios-add-modal')
        @endcan
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
