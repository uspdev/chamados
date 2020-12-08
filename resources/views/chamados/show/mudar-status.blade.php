@section('styles')
@parent
<style>
    #btn_salvar_status {
        display: none;
    }
</style>
@endsection

<div class="col-sm form-group mt-2">
    <label class="col-form-label col-4" for="status"><b>Status:</b></label>
    <div class="col-sm">
        <form id="status_form" name="status_form" method="POST" action="chamados/{{$chamado->id}}">
            @csrf
            @method('PUT')
            {{ Form::select('status', $status_list, $chamado->status, ['id'=>'estado', 'class'=>'form-control col-8'] ) }}
            {{ Form::submit('Salvar', ['id'=>'btn_salvar_status', 'class'=>'btn btn-primary']) }}
        </form>
    </div>
</div>
