@extends('master')

@section('content')
    @parent
        @auth
            <script>window.location = "chamados";</script>
        @else
        Você ainda não fez seu login com a senha única USP <a href="login"> Faça seu Login! </a><br /><br />
        <a href="loginlocal"> Acesso para usuário sem senha única USP </a>
        @endauth
@endsection
