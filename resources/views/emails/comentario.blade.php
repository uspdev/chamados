<div>
    <b>Autor(a):</b> <br>
    Número USP: {{ $autor->codpes }} <br>
    Nome: {{ $autor->name }}<br>
    Chamado: #{{ $chamado->nro }}/{{ $chamado->created_at->format('Y') }}
</div>

<br>
<b>Status:</b> {{ $chamado->status }}<br>
<b>Por:</b> {{ $comentario->user->name }}<br>

<div>
    <b>Comentário:</b> {!! nl2br($comentario->comentario) !!}
</div>

Sistema de chamados. Para comentar/responder acesse:

{{config('app.url')}}
