@extends('master')

@section('title', 'Dashboard')

@section('content_header')
@stop

@section('content')
@parent

<div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Site</th>
        <th>Chamado</th>
      </tr>
    </thead>

    <tbody>

@forelse ($chamados->sortByDesc('created_at') as $chamado)
      <tr>
        <td> 
            <ul style="list-style-type: none;">
              <li> <b>id:</b> {{ $chamado->id }} </li>
              <li> <b>site:</b>{{ $chamado->site->dominio.config('sites.dnszone') }}</li>
              <li> <b>por:</b> {{ $chamado->user->name }}</li>
              <li> <b>em:</b> {{ Carbon\Carbon::parse($chamado->created_at)->format('d/m/Y H:i') }}</li>
              <li> <b>status:</b>{{ $chamado->status }}</li>
              <li> <b>tipo:</b> {{ $chamado->tipo }}</li>
           </ul>
        </td>
        <td><a href="/chamados/{{$chamado->site_id}}/{{$chamado->id}}">{!! $chamado->descricao !!}</a></td>
      </tr>
@empty
    <tr>
        <td colspan="7">Não há chamados abertos</td>
    </tr>
@endforelse
</tbody>
</table>

</div>


@stop
