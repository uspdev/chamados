@extends('master')

@section('title', 'Categorias')

@section('content_header')
@stop

@section('content')
@parent

<a href="{{ route('categorias.create') }}" class="btn btn-success">Nova categoria</a>
<br><br>
<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">Categoria</th>
      <th scope="col">Ações</th>
    </tr>
  </thead>
  <tbody>
    @forelse($categorias as $categoria)
    <tr>
      <td>{{ $categoria->nome }}</td>
      <td>
        <a href="categorias/{{ $categoria->id }}/edit"><i class="fas fa-edit"></i></a>
        <form method="POST" action="categorias/{{ $categoria->id }}" style="display:inline">
            {{csrf_field()}} {{ method_field('delete') }}
            <button type="submit" class="delete-item btn"><i class="fas fa-trash-alt"></i></button>
        </form>
      </td>
    </tr>
    @empty
    <tr>
      <td> Não há categorias cadastradas </td>
      <td></td>
    </tr>
    @endforelse
  </tbody>
</table>

@stop
