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
        {{-- Aqui vamos usar gate proprio pois poderá comentar se não estiver finalizado --}}
        @includewhen(Gate::check('updateComentario', $chamado),'chamados.show.comentarios-add-modal')
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
    /* Permitir links nos comentários */
    
    /* Função que retorna true se o texto for em formato url
       Retorna false se o texto começar com a tag a '<a href...'
       https://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-a-url/5717133#5717133
    */
    function validURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!pattern.test(str);
    }
    /* Procura palavra por palavra formato de url e converte em tag html 
       Precisa considerar as quebras de linha
    */
    $(function() {
        $("#comentario").on("blur", function() {
            var text = $(this).val();
            var array = text.trim().split(/\s+/);
            var html = '';
            for (var i = 0; i < array.length; i++) {
                if (validURL(array[i])) {
                    if (array[i].search("http://") === -1) {
                        array[i] = "http://" + array[i];
                    }
                    array[i] = "<a href='" + array[i] + "' target='_blank'>" + array[i] + "</a>"; 
                }
                html += array[i] + " ";
            }
            $(this).val(html);
        })
    })
</script>
@endsection