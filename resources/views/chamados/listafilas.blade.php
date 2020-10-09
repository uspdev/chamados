@extends('master')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@parent

<ul>
@foreach ($filas as $fila)
  <li><a href="{{ route('chamados.create', $fila->id) }}">{{ $fila->setor->nome }} - {{ $fila->nome }}</a></li>
@endforeach
</ul>

@stop
