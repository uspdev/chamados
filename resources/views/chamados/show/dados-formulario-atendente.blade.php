@section('styles')
@parent
<style>
    #btn_salvar_form_atendente {
        display: none;
    }
</style>
@endsection

<div class="ml-2">    
    <div class="form-group">       
        <form id="form_atendente" name="form_atendente" method="POST" action="chamados/{{$chamado->id}}">
            @csrf
            @method('PUT')
            @foreach ($formAtendente as $input)  
                @foreach ($input as $element)
                    {{ $element }}                      
                @endforeach 
                <br>
            @endforeach
            {{ Form::submit('OK', ['id'=>'btn_salvar_form_atendente', 'class'=>'btn btn-primary']) }}
        </form>
    </div>
</div>