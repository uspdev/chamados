@include('emails.cabecalho')
<br>
@include('emails.dados_chamado')
<br>
<div style="background-color: AliceBlue;
        padding: 5px;">
    <b>Novo comentário adicionado</b><br>
    <b>Por:</b> {{ $comentario->user->name }} em {{ $comentario->created_at->format('d/m - H:i') }}<br>
    <b>Comentário:</b><br>
    <div>
        {!! nl2br($comentario->comentario) !!}
    </div>
</div>
<br>
@include('emails.rodape')
