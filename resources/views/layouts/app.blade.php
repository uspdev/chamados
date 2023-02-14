@extends('laravel-usp-theme::master')

@section('styles')
  @parent
  <link rel="stylesheet" href="css/chamados.css">
  <style>
    .atendente-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: orange !important;
    }

    .admin-menubar {
      border-bottom-style: solid !important;
      border-bottom-width: medium !important;
      border-bottom-color: red !important;
    }

  </style>
@endsection

@section('content')
  @include('messages.flash')
  @include('messages.errors')
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    $(function() {

      // vamos confirmar ao apagar um registro
      $(".delete-item").on("click", function() {
        return confirm("Tem certeza que deseja deletar?")
      })

      // ativando tooltip global
      $('[data-toggle="tooltip"]').tooltip({
        container: 'body',
        html: true
      })

      // vamos aplicar o estilo de perfil no menubar
      @if (session('perfil') == 'atendente')
        $('#menu').find('.navbar').addClass('atendente-menubar')
      @endif
      @if (session('perfil') == 'admin')
        $('#menu').find('.navbar').addClass('admin-menubar')
      @endif

    })
  </script>
@endsection
