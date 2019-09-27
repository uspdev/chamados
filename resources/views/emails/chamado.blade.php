<h1>Novo chamado para o site {{ $chamado->site->dominio.config('sites.dnszone') }}</h1>

<div>
<b>Solicitante:</b> <br>
Número USP: {{ $user->codpes }} <br>
Nome: {{ $user->name }} 
</div>

<br>

<b>Tipo:</b> {{ $chamado->tipo }}
<div>
<b>Chamado:</b> {!! $chamado->descricao !!}
</div>

Sistema de sites fflch: https://sites.fflch.usp.br/

