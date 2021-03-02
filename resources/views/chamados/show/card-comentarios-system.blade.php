@section('styles')
@parent
<style>
    #card-comentarios-system {
        font-size: 0.95em;
        border: 1px solid gray;
        border-top: 3px solid gray;
    }

</style>
@endsection

<div class="card bg-light mb-3 border-black" id="card-comentarios-system">
    <div class="card-header">
        Registros do chamado
        <span class="badge badge-pill badge-primary">{{ $chamado->comentarios->where('tipo','system')->count() }}</span>
    </div>
    <div class="card-body">
        @forelse ($chamado->comentarios->where('tipo','system')->sortByDesc('created_at') as $comentario)
        <div class="">
            <b>{{ $comentario->user->name }}</b> - {{ $comentario->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="ml-2">
            <p class="card-text">{!! $comentario->comentario !!}</p>
        </div>
        <hr />
        @empty
        Não há registros
        @endforelse
    </div>
</div>
