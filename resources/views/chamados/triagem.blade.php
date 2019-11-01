@extends('master')

@section('title', 'Chamado')

@section('content_header')
@stop

@section('javascripts_bottom')
    @parent
    <script>CKEDITOR.replace( 'chamado' );</script>
@stop

@section('content')
  @parent

  <form method="POST" role="form" action="{{ route('chamados.update', $chamado ) }}">
    @csrf
    {{ method_field('patch') }}
@can('admin')
<div class="row">
    <div class="col-sm form-group">
        <label for="atribuido_para"><b>Atribuir para:</b></label>
        <select name="atribuido_para" class="form-control">
            <option value="" selected="">Escolher</option>
            @foreach($atendentes as $atendente)
                @if(old('atribuido_para') == '' and isset($chamado->atribuido_para))
                    <option value="{{ $atendente[0] }}" {{ ( $chamado->atribuido_para == $atendente[0]) ? 'selected' : ''}}>
                        {{ $atendente[1] }}
                    </option>                
                @else
                    <option value="{{ $atendente[0] }}" {{ (old('atribuido_para') == $atendente[0]) ? 'selected' : ''}}>
                        {{ $atendente[1] }}
                    </option>   
                @endif
            @endforeach
        </select>
    </div>

    <div class="col-sm form-group">
        <label for="complexidade"><b>Complexidade:</b></label>
        <select name="complexidade" class="form-control">
            <option value="" selected="">Escolher</option>
            @foreach($complexidades as $complexidade)
                @if(old('complexidade') == '' and isset($chamado->complexidade))
                    <option value="{{ $complexidade }}" {{ ( $chamado->complexidade == $complexidade) ? 'selected' : ''}}>
                        {{ $complexidade }}
                    </option>                
                @else
                    <option value="{{ $complexidade }}" {{ (old('complexidade') == $complexidade) ? 'selected' : ''}}>
                        {{ $complexidade }}
                    </option>   
                @endif
            @endforeach
        </select>
    </div>

</div>
@endcan



<div class="form-group">
    <button type="submit" class="btn btn-primary">Enviar</button>
</div>
  </form>

@stop
