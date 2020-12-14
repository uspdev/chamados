@extends('master')

@section('content')
@parent
<div class="h4 text-danger">Recurso inexistente!</div>
Você tentou acessar um recurso inexistente. Se acredita que deveria existir, contate a equipe de suporte! 
<br>
<br>
<a href="javascript:history.back()">Voltar à página anterior</a>

@endsection
