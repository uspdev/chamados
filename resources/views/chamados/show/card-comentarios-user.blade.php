@section('styles')
@parent
<style>
    #card-comentarios-user {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }
</style>
@endsection

<div class="card bg-light mb-3 comentarios-user" id="card-comentarios-user">
    <div class="card-header">
        Comentários
        <span class="badge badge-pill badge-primary">{{ $chamado->comentarios->where('tipo','user')->count() }}</span>
        @can('update',$chamado)
        @include('chamados.show.comentarios-add-modal')
        @endcan
    </div>
    <div class="card-body">
        @forelse ($chamado->comentarios->where('tipo','user')->sortByDesc('created_at') as $comentario)
        <div class="">
            <b>{{ $comentario->user->name }}</b> - {{ $comentario->created_at->format('d/m/Y H:i') }}
        </div>
        <div class="ml-2">
            <p class="card-text" id="comentario">{!! nl2br($comentario->comentario) !!}</p>
        </div>
        <hr />
        @empty
        Não há comentários
        @endforelse
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    /* Permitir links nos comentários
       https://stackoverflow.com/questions/60716750/textarea-in-laravel-view-should-show-link-if-given
    */
    $(function() {
        $("#comentario").on("blur", function() {
            var text = $(this).val();
            var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            var text1 = text.replace(exp, "<a href='$1'>$1</a>");
            var exp2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
            $(this).val(text1.replace(exp2, '$1<a target="_blank" href="http://$2">$2</a>'));
        })
    })
</script>
@endsection