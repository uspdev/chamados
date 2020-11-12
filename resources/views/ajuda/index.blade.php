@extends('master')

@section('content')
@parent
<div class="card bg-light mb-3">
    <div class="card-header h5">
        Ajuda
    </div>
    <div class="card-body">
        <a href="ajuda/manual_usuario">Manual do usuário</a><br><br>
        <a href="ajuda/manual_atendente">Manual do atendente</a><br><br>

        @markdown
        ##### Perguntas e respostas

        **P. Qual email e telefone o sistema usa?**\
        **R.** Em geral o email e o telefone são obtidos da base corporativa da USP. 
        Em alguns casos você mesmo pode alterar essa informação acessando o sistema correspondente,
        em outros casos pode ser necessário contatar o serviço de pessoal que atende a sua Unidade.
        @endmarkdown
    </div>
</div>

@endsection
