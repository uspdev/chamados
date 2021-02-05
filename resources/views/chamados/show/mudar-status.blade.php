@section('styles')
    @parent
    <style>
        #btn_salvar_status {
            display: none;
        }

    </style>
@endsection

<div class="form-group ml-2">
    <form id="status_form" name="status_form" method="POST" action="chamados/{{ $chamado->id }}">
        @csrf
        @method('PUT')
        {{ Form::label('status', 'Status') }}
        {{ Form::select('status', $status_list, $chamado->status, ['id' => 'estado', 'class' => 'form-control', 'placeholder' => 'Escolha um..']) }}
        {{ Form::submit('OK', ['id' => 'btn_salvar_status', 'class' => 'btn btn-primary mt-2']) }}
    </form>
</div>
