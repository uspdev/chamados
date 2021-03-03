@extends('master')

@section('content')
@parent
<div class="row">
    <div class="col-md-12 form-inline">
        <div class="d-none d-sm-block h4 mt-2">Meus Chamados <i class="fas fa-search"></i></div>
        <div class="d-block d-sm-none h4 mt-2"><i class="fas fa-search"></i></div>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
        @include('chamados.partials.mostra-ano')

    </div>
</div>

<table class="table table-striped meus-chamados display responsive" style="width:100%">
    <thead>
        <tr>
            <th>Nro</th>
            <th>Assunto</th>
            <th>Atendente</th>
            <th>Autor</th>
            <th>Fila</th>
            <th class="text-right">Aberto em</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($chamados as $chamado)
        @php
            $color = $chamado->fila->getColortoLabel($chamado->status);
        @endphp
        <tr>
            <td> {{ $chamado->nro }}</td>
            <td>
                @include('chamados.partials.status-small')
                <a href="chamados/{{$chamado->id}}"> {!! $chamado->assunto !!} </a>
                @include('chamados.partials.status-muted')
            </td>
            <td>
                @php($user = $chamado->users()->wherePivot('papel', 'Atendente')->first())
                {{ Str::limit($user->name ?? '-', 20) }}
                @includewhen($user, 'chamados.show.user-detail', ['user'=>$user])
            </td>
            <td>
                @php($user = $chamado->users()->wherePivot('papel', 'Autor')->first())
                {{ Str::limit($user->name ?? 'Sem autor !!', 20) }}
                @includewhen($user, 'chamados.show.user-detail', ['user'=>$user])
            </td>
            <td> ({{ $chamado->fila->setor->sigla }}) {{ $chamado->fila->nome }}</td>
            <td class="text-right">
                @if($chamado->created_at->format('d/m/Y') == date('d/m/Y'))
                Hoje, {{ $chamado->created_at->format('H:i') }}
                @else
                {{ $chamado->created_at->format('d \d\e M.') }}
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">Não há chamados</td>
        </tr>
        @endforelse

    </tbody>
</table>
@stop

@section('javascripts_bottom')
@parent
<script>
    $(document).ready(function() {

        oTable = $('.meus-chamados').DataTable({
            dom: 't'
            , "paging": false
            , "sort": true
            , "order": [
                [0, "desc"]
            ]
        });

    })

</script>
@endsection
