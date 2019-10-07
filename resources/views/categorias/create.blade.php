@extends('master')

@section('title', 'Nova Categoria')

@section('content')
  @parent
  <form method="POST" role="form" action="{{ route('categorias.store') }}">
    @csrf
    @include('categorias/form')
  </form>

@stop
