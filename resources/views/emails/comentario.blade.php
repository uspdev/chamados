<div>
<b>Autor(a):</b> <br>
Número USP: {{ $user->codpes }} <br>
Nome: {{ $user->name }}
Chamado: #{{ $comentario->chamado->id }}
</div>

<br>
<b>Status:</b> {{ $comentario->chamado->status }}
<div>
<b>Comentário:</b> {!! $comentario->comentario !!}
</div>

Sistema de chamados da Seção Técnica de Informática
da FFLCH, para comentar/responder acesse:

https://sisinfo.fflch.usp.br/


