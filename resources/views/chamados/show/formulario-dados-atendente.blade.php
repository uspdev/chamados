@section('styles')
  @parent
  <style>
    #btn_salvar_form_atendente {
      display: none;
    }

  </style>
@endsection

<b>Formulário</b><br>
<div class="ml-2">
  @foreach ($template as $field => $val)
    @if (!empty($val->can) && $val->can == 'atendente')
      <span class="text-muted">{{ $val->label }}:</span>
      @switch($val->type)
        @case('date')
        <span>{{ Carbon\Carbon::parse($extras->$field)->format('d/m/Y') ?? '' }}</span>
        @break
        @default
        <span>{{ $extras->$field ?? '' }}</span>
      @endswitch
      <br>
    @endif
  @endforeach
</div>
