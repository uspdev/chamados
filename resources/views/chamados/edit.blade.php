@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
    @parent
@stop

@section('content')
  @parent
  <h2> {{ $fila->setor->nome }} - {{ $fila->nome }} </h2>
  <form method="POST" role="form" action="{{ route('chamados.update', $chamado ) }}">
    @csrf
    {{ method_field('patch') }}
    @include('chamados/form')
  </form>

@stop
