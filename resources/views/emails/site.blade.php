<h1>Nova solicitação de site</h1>

<div>
<b>Site solicitado:</b> {{ $site->dominio.config('sites.dnszone') }}
</div>

<br>

<div>
<b>Solicitante:</b> <br>
Número USP: {{ $user->codpes }} <br>
Nome: {{ $user->name }} 
</div>

<br>

<div>
<b>Justificativa:</b> {!! $site->justificativa !!}
</div>

Sistema de sites fflch: https://sites.fflch.usp.br/

