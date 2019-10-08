@extends('master')

@section('title', 'Nova Categoria')

@section('content')
  @parent
  <form method="POST" role="form" action="{{ route('categorias.update', $categoria) }}">
    @csrf
    {{ method_field('patch') }}
    @include('categorias/form')
  </form>

@stop
