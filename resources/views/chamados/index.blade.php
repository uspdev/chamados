@extends('master')

@section('content')
@parent
<div class="row">
    <div class="col-md-12 form-inline">
        <div class="d-none d-sm-block h4 mt-2">
            {{-- vai mostrar no desktop --}}
            Meus Chamados
        </div>
        <div class="d-block d-sm-none h4 mt-2">
            {{-- vai mostrar no mobile --}}
            <i class="fas fa-search"></i>
        </div>
        <div class="h4 mt-1 ml-2">
            <span class="badge badge-pill badge-primary datatable-counter">-</span>
        </div>
        @include('partials.datatable-filter-box', ['otable'=>'oTable'])
        @include('chamados.partials.mostrar_finalizados')
        @include('chamados.partials.mostra-ano')
    </div>
</div>

<table class="table table-striped meus-chamados display responsive" style="width:100%">
    <thead>
        <tr>
            <th>Nro</th>
            <th></th>
            <th>Assunto</th>
            <th>Atendente</th>
            <th>Autor</th>
            <th>Fila</th>
            <th class="text-right">Aberto em</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($chamados as $chamado)
        <tr>
            <td> {{ $chamado->nro }}</td>
            <td>
                @include('chamados.partials.status-small')
            </td>
            <td>
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
        @endforeach

    </tbody>
</table>
@stop

@section('javascripts_bottom')
@parent
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
<script>
    $(document).ready(function() {

        oTable = $('.meus-chamados').DataTable({
            dom: 't'
            , "paging": false
            , "sort": true
            , "order": [
                [0, "desc"]
            ]
            , "fixedHeader": true
            , columnDefs: [{
                targets: 1
                , orderable: false
            }]
            , language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json'
            }
        });

        // recuperando o storage local
        var datatableFilter = localStorage.getItem('datatableFilter')
        $('#dt-search').val(datatableFilter);

        // vamos aplicar o filtro
        oTable.search($('#dt-search').val()).draw()

        // vamos renderizar o contador de linhas
        $('.datatable-counter').html(oTable.page.info().recordsDisplay)

        // vamos guardar no storage à medida que digita
        $('#dt-search').keyup(function() {
            localStorage.setItem('datatableFilter', $(this).val())
        })

    })

</script>
@endsection
