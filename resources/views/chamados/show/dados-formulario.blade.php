<span class="font-weight-bold">Formulário</span><br>
<div class="ml-2">
    @foreach($template as $field => $val)

    <span class="text-muted">{{ $val->label }}:</span>
    <span>{{ $extras->$field ?? '' }}</span>
    @if(!empty($val->can) && $val->can == 'admin')
    Este campo so deve aparecer para admin
    @endif
    <br>
    @endforeach
</div>

<br>
