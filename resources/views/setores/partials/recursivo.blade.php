@foreach($setor->setores as $setor)
<table class="table table-sm my-0 ml-3">
    <tr>
        <td>
            @include('setores.partials.setor')
            @if ($setor->setores->isNotEmpty())
            @include('setores.partials.recursivo')
            @endif
        </td>
    </tr>
</table>
@endforeach
