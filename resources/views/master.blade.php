@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="/css/sites.css">
@stop

@section('javascripts_head')
    @parent
    <script src="/js/atendimento.js"></script>
@stop

@section('content')
    @include('messages.flash')
    @include('messages.errors')
@stop

@section('footer')
FFLCH sites
@stop
