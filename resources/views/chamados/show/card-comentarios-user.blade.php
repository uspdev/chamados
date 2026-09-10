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
            @if ($comentario->foiEditado())
                <span class="badge badge-secondary" title="Editado em {{ $comentario->updated_at->format('d/m/Y H:i') }}">editado</span>
            @endif
            @if ($comentario->podeSerEditadoPor(Auth::user()))
                <button type="button" class="btn btn-sm btn-light text-primary py-0 ml-1" data-toggle="modal"
                    data-target="#editarComentarioModal{{ $comentario->id }}" title="Editar comentário">
                    <i class="far fa-edit"></i>
                </button>
            @endif
        </div>
        <div class="ml-2">
            <p class="card-text">{!! nl2br($comentario->comentario) !!}</p>
        </div>
        @if ($comentario->podeSerEditadoPor(Auth::user()))
            <div class="modal fade" id="editarComentarioModal{{ $comentario->id }}" data-backdrop="static" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar comentário</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{ html()->form('PUT', 'comentarios/' . $comentario->id)->open() }}
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control comentario-linkify" name="comentario" rows="7" required>{{ $comentario->comentario }}</textarea>
                                <small class="form-text text-muted">
                                    Aceita tags HTML básicas. URLs são convertidas automaticamente em links, exceto quando estiverem entre aspas simples.
                                </small>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
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

    function hasOddSingleQuotes(str) {
        var quotes = str.match(/'/g);
        return quotes !== null && quotes.length % 2 === 1;
    }

    /* Procura palavra por palavra formato de url e converte em tag html */
    $(function() {
        $("textarea[name='comentario']").on("blur", function() {
            var text = $(this).val();
            var text1 = text.replace(/(?:\r\n|\r|\n)/g, ' <br> ');
            var array = text1.trim().split(/\s+/);
            var html = '';
            var insideSingleQuotes = false;
            for (var i = 0; i < array.length; i++) {
                var ignoreLink = insideSingleQuotes || array[i].indexOf("'") !== -1;
                if (!ignoreLink && validURL(array[i])) {
                    if (array[i].search('http://') === -1 && array[i].search('https://') === -1) {
                        array[i] = "https://" + array[i];
                    }
                    array[i] = "<a href='" + array[i] + "' target='_blank'>" + array[i] + "</a>";
                }
                if (hasOddSingleQuotes(array[i])) {
                    insideSingleQuotes = !insideSingleQuotes;
                }
                html += array[i] + " ";
            }
            $(this).val(html);
        })
    })
</script>
@endsection
