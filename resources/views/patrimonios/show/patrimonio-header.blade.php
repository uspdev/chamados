<div class="d-flex">
  <a class="text-decoration-none d-block text-truncate" type="button" data-toggle="collapse"
    data-target="#patrimonio_{{ $patrimonio->numpat }}" aria-expanded="true" aria-controls="collapseOne"
    title="{{ $patrimonio->replicado()->epfmarpat ?? '-' }}; {{ $patrimonio->replicado()->tippat ?? '-' }}; {{ $patrimonio->replicado()->modpat ?? '-' }}">
    <b>{{ $patrimonio->numFormatado() }}</b>
    {{ ($epfmarpat = $patrimonio->replicado()->epfmarpat) ? "| $epfmarpat;" : '' }}
    {{ ($tippat = $patrimonio->replicado()->tippat) ? " $tippat;" : '' }}
    {{ ($modpat = $patrimonio->replicado()->modpat) ? " $modpat" : '' }}
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
