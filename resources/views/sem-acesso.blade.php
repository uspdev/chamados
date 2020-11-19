@extends('master')

@section('content')
@parent
<div class="h4 text-danger">Sem acesso!</div>
Você tentou acessar um recurso não autorizado. Se acredita que deveria ter acesso, contate a equipe de suporte! 
<br>
<br>
<a href="javascript:history.back()">Voltar à página anterior</a>

@endsection
