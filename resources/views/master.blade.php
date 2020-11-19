@extends('laravel-usp-theme::master')

@section('styles')
@parent
<link rel="stylesheet" href="css/chamados.css">
@endsection

@section('content')
@include('messages.flash')
@include('messages.errors')
@endsection

@section('javascripts_bottom')
@parent
<script>
    $(function() {
        $(".delete-item").on("click", function() {
            return confirm("Tem certeza que deseja deletar?");
        });

        $('[data-toggle="tooltip"]').tooltip({
            container: 'body'
            , html: true
        });
    });

</script>
@endsection
