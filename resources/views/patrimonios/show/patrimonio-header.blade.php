<div class="d-flex">
  <a class="text-decoration-none" type="button" data-toggle="collapse"
    data-target="#patrimonio_{{ $patrimonio->numpat }}" aria-expanded="true" aria-controls="collapseOne">
    <b>{{ $patrimonio->numFormatado() }}</b>
    {{-- se tiver replicado mostra descrição --}}
    @if ($patrimonio->replicado()->epfmarpat)
      | {{ $patrimonio->replicado()->epfmarpat }};
      {{ $patrimonio->replicado()->tippat ?? '-' }};
      {{ $patrimonio->replicado()->modpat ?? '-' }}
    @endif
  </a>
  <div class="hidden-btn d-none ml-auto">
    @includewhen(Gate::check('update', $chamado), 'common.btn-delete-sm', [
        'action' => 'chamados/' . $chamado->id . '/patrimonios/' . $patrimonio->id,
    ])
  </div>
</div>

@once
  @section('javascripts_bottom')
    @parent
    <script>
      $(function() {
        $('.patrimonio-item').hover(
          function() {
            $(this).find('.hidden-btn').removeClass('d-none');
          },
          function() {
            $(this).find('.hidden-btn').addClass('d-none');
          }
        )
      });
    </script>
  @endsection
@endonce
