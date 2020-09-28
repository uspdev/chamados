@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    {{-- <link rel="stylesheet" href="/css/sites.css"> --}}
@stop

@section('javascripts_head')
    @parent
    <script src="/js/atendimento.js"></script>
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@stop

@section('content')
    @include('messages.flash')
    @include('messages.errors')
@stop