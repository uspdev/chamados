@extends('laravel-usp-theme::master')

@section('content')
    @include('messages.flash')
    @include('messages.errors')

    <div>
        <a href="{{ route('users.create') }}" class="btn btn-success">
        Autorizar novo atendente
        </a>
    </div>

    <br>

    <h3>Atendentes:</h3>
        <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número USP</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                    <td>{{ $user->codpes }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        <form method="POST" action="users/{{$user->id}}">
                        @csrf
                        @method('delete')
                        <button onclick ="return confirm('Tem certeza que deseja deletar?');" type="submit" style="background-color: transparent;border: none;"><i class="far fa-trash-alt" color="#007bff"></i></button>
                        </form>
                    </td>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
