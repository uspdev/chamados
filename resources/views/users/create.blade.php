@extends('laravel-usp-theme::master')

@section('content')
    @include('messages.flash')
    @include('messages.errors')


<form method="POST" class="form-inline" action="users">
@csrf
    <div class="form-group">
        <label> Número USP do novo atendente: </label>
        <div class="col-sm-3">
            <input name="numero_usp" class="form-control" value="{{old('numero_usp')}}">
        </div>
    </div>
  
    <button type="submit" class="btn btn-primary"> Enviar </button>
</form>

@endsection