@extends('master')
@section('content')
@parent
<h3>Criar template para a fila: {{ $fila->nome }}</h3>
<form method="POST" action="{{ route('filas.storetemplate', $fila->id) }}">
  @csrf
  <div class="form-row">
    <div class="col"><strong>campo</strong></div>
    @foreach ($fila->getTemplateFields() as $field)
      <div class="col"><strong>{{ $field }}</strong></div>
    @endforeach
    <div class="col"></div>
  </div>
  @if ($template)
    @foreach ($template as $tkey => $tvalue)
      <div class="form-row">
        <div class="col">{{ $tkey }}</div>
        @foreach ($fila->getTemplateFields() as $field)
          <div class="col">
            @isset($tvalue[$field])
              <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="{{ is_array($tvalue[$field]) ? json_encode($tvalue[$field]) : $tvalue[$field] ?? '' }}">
            @endisset
            @empty($tvalue[$field])
              <input class="form-control" name="template[{{ $tkey }}][{{ $field }}]" value="">
            @endempty
          </div>
        @endforeach
        <div class="col">admin</div>
      </div>
    @endforeach
  @endif
  <div class="form-row">
    <div class="col"><input class="form-control" name="campo"></div>
    @foreach ($fila->getTemplateFields() as $field)
      <div class="col"><input class="form-control" name="new[{{ $field }}]"></div>
    @endforeach
    <div class="col"><button class="btn btn-primary" type="submit">Salvar</button></div>
  </div>
</form>
@endsection
