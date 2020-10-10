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
  <h2> {{ $fila->setor->nome }} - {{ $fila->nome }} </h2>
  <form method="POST" role="form" action="{{ route('chamados.store', $fila->id) }}">
    @csrf
    @include('chamados/form')
  </form>

@stop
