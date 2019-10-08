@extends('master')

@section('title', 'Novo Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
    @parent
    <script>CKEDITOR.replace( 'chamado' );</script>
@stop

@section('content')
  @parent
  <form method="POST" role="form" action="{{ route('chamados.store') }}">
    @csrf
    @include('chamados/form')
  </form>

@stop
