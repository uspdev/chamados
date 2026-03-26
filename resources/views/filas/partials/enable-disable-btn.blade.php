@section('styles')
@parent
{{-- https://stackoverflow.com/questions/50349017/how-can-i-change-cursor-for-disabled-button-or-a-in-bootstrap-4 --}}
<style>
    button:disabled {
        cursor: not-allowed;
        pointer-events: all !important;
    }

</style>
@endsection

{{ html()->form('PUT', 'filas/'.$fila->id)->open() }}
<div class="btn-group enable-disable-btn">
    <button type="submit" class="btn btn-sm {{($fila->estado == 'Em elaboração') ? 'btn-warning' : 'btn-secondary'}}" {{($fila->estado == 'Em elaboração') ? 'disabled' : ''}} name="estado" value="Em elaboração">
        Em elaboração
    </button>
    <button type="submit" class="btn btn-sm {{($fila->estado == 'Em produção') ? 'btn-success' : 'btn-secondary'}}" {{($fila->estado == 'Em produção') ? 'disabled' : ''}} name="estado" value="Em produção">
        Em produção
    </button>
    <button type="submit" class="btn btn-sm {{($fila->estado == 'Desativada') ? 'btn-danger' : 'btn-secondary'}}" {{($fila->estado == 'Desativada') ? 'disabled' : ''}} name="estado" value="Desativada">
        Desativada
    </button>
</div>
{{ html()->form()->close() }}
