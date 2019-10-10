@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
    @parent
    <script>CKEDITOR.replace( 'chamado' );</script>
@stop

@section('content')
  @parent

  <form method="POST" role="form" action="{{ route('chamados.update', $chamado ) }}">
    @csrf
    {{ method_field('patch') }}
    @include('chamados/form')
  </form>

@stop
