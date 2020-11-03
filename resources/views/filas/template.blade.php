@extends('master')
@section('content')
@parent
<h3>Criar template para a fila: {{ $fila->nome }}</h3>
<form id="template-form" method="POST" action="{{ route('filas.storetemplate', $fila->id) }}">
  @csrf
  <div id="template-header" class="form-row">
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
        <div class="col">
            <button class="btn btn-danger" type="button" onclick="apaga_campo(this)">Apagar</button>
            <button class="btn btn-success" type="button" onclick="move(this, 1)">&#8679;</button>
            <button class="btn btn-success" type="button" onclick="move(this, 0)">&#8681;</button>
        </div>
      </div>
    @endforeach
  @endif
  <div id="template-new" class="form-row">
    <div class="col"><input class="form-control" name="campo"></div>
    @foreach ($fila->getTemplateFields() as $field)
      <div class="col"><input class="form-control" name="new[{{ $field }}]"></div>
    @endforeach
    <div class="col"><button class="btn btn-primary" type="submit">Salvar</button></div>
  </div>
</form>
@endsection

@section('javascripts_bottom')
@parent
<script>
function apaga_campo(r) {
    var row = r.parentNode.parentNode;
    row.remove();
    var form = document.getElementById("template-form");
    form.requestSubmit();
}

function move(r, up) {
    var head = "template-header";
    var tail = "template-new";
    var form = document.getElementById("template-form");
    var row = r.parentNode.parentNode;
    if (up) {
        var sibling = row.previousElementSibling;
        if (sibling.id != head) {
            row.parentNode.insertBefore(row, sibling);
            form.requestSubmit();
        }
    }
    else {
        var sibling = row.nextElementSibling;
        if (sibling.id != tail) {
            row.parentNode.insertBefore(row, sibling.nextSibling);
            form.requestSubmit();
        }
    }
}
</script>
@endsection
