@if ($chamado->fila->settings()->get('instrucoes'))
  <span class="badge badge-primary hand-cursor" data-toggle="collapse" data-target="#instrucoes">
    Instruções <i class="fas fa-caret-down"></i>
  </span>
@endif

@Once
  @section('styles')
    @parent
    <style>
      .hand-cursor {
        cursor: pointer;
      }

    </style>
  @endsection
@endonce
