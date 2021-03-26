<div style="background-color: AliceBlue; padding: 5px;">
    <b>Status:</b> {{ $chamado->status }}<br>
    <b>Autor:</b> {{ $autor->name }}<br>
    Chamado no. {{ $chamado->nro }}/{{ $chamado->created_at->format('Y') }}
    para ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}<br>
    Assunto: {{ $chamado->assunto }}<br>
    Descrição: {!! nl2br($chamado->descricao) !!}<br>
    Link direto: <a href="{{config('app.url')}}/chamados/{{$chamado->id}}">{{config('app.url')}}/chamados/{{$chamado->id}}</a><br>
</div>
