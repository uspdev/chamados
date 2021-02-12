<div>
    ----------<br>
    Seu papel neste chamado: <b>{{ $papel }}</b><br>
    Este é um email automático - não responda. Para interagir com o chamado acesse:<br>
    <a href="{{config('app.url')}}/chamados/{{$chamado->id}}">Interagir com o chamado</a><br>
    <br>
    Sistema {{config('app.name')}}.<br>
</div>
