@extends('laravel-usp-theme::master')

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.4/dist/select2-bootstrap4.min.css">
    {{-- <link rel="stylesheet" href="css/sites.css"> --}}
@endsection

@section('javascripts_head')
    @parent
    <script src="js/atendimento.js"></script>
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@endsection

@section('javascripts_bottom')
    @parent
@endsection

@section('content')
    @include('messages.flash')
    @include('messages.errors')
@endsection