@section('styles')
@parent
<style>
    #btn_salvar_complexidade {
        display: none;
    }
</style>
@endsection

<div class=" form-group mt-2">        
        <form id="complexidade_form" name="complexidade_form" method="POST" action="chamados/{{$chamado->id}}">
            @csrf
            @method('PUT')
            <b>Complexidade:</b>
            {{ Form::select('complexidade', $complexidades, $chamado->complexidade, ['id'=>'complexidade', 'class'=>'form-control', 'placeholder'=>'Escolha uma..'] ) }}
            {{ Form::submit('OK', ['id'=>'btn_salvar_complexidade', 'class'=>'btn btn-primary']) }}
        </form>
    
</div>