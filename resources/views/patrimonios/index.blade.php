@extends('layouts.app')

@section('content')
  @parent
  @include('laravel-usp-theme::blocos.datatable-simples')

  <table class="table table-sm datatable-simples">
    <thead>
      <tr>
        <th>Numpat</th>
        <th>Chamados</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($patrimonios as $p)
        <tr>
          <td><a href="{{ route('patrimonios.show', $p->numpat) }}">{{ $p->numpat }}</a></td>
          <td>{{ $p->chamados->count() }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
