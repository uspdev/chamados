<span class="font-weight-bold">Formulário</span><br>
@foreach($template as $field => $val)
<span class="text-muted">{{ $val->label }}:</span>
<span>{{ $extras->$field ?? '' }}</span><br>
@endforeach
<br>
