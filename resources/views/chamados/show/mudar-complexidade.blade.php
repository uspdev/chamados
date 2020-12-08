@section('styles')
@parent
<style>
    #btn_salvar_complexidade {
        display: none;
    }
</style>
@endsection

<div class="col-sm form-group mt-2">
    <label class="col-form-label col-4" for="complexidade"><b>Complexidade:</b></label>
    <div class="col-sm">
        <form id="complexidade_form" name="complexidade_form" method="POST" action="chamados/{{$chamado->id}}">
            @csrf
            @method('PUT')
            {{ Form::select('complexidade', $complexidades, $chamado->complexidade, ['id'=>'complexidade', 'class'=>'form-control col-8'] ) }}
            {{ Form::submit('Salvar', ['id'=>'btn_salvar_complexidade', 'class'=>'btn btn-primary']) }}
        </form>
    </div>
</div>