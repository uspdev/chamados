@extends('laravel-usp-theme::master')

@section('styles')
@parent
<link rel="stylesheet" href="css/chamados.css">
@endsection

@section('javascripts_head')
@parent
@endsection

@section('javascripts_bottom')
@parent
<script src="js/atendimento.js"></script>
<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@endsection

@section('content')
@include('messages.flash')
@include('messages.errors')
@endsection
