@section('styles')
  @parent
  <style>
    #card-patrimonios {
      border: 1px solid brown;
      border-top: 3px solid brown;
    }

  </style>
@endsection

<a name="card_patrimonios"></a>
<div class="card bg-light mb-3" id="card-patrimonios">
  <div class="card-header">
    Patrimônios
    <span class="badge badge-pill badge-primary">{{ $chamado->patrimonios->count() }}</span>
    @includewhen(Gate::check('update', $chamado), 'patrimonios.partials.patrimonio-add-modal')
  </div>
  <div class="card-body">
    <div class="accordion" id="accordionPatrimonios">
      @foreach ($chamado->patrimonios as $patrimonio)
        <div class="card patrimonio-item">
          <div class="card-header" style="font-size:15px">
            @include('patrimonios.show.patrimonio-header')
          </div>
          <div id="patrimonio_{{ $patrimonio->numpat }}" class="collapse" aria-labelledby="headingOne"
            data-parent="#accordionPatrimonios">
            <div class="card-body">
              @include('patrimonios.show.patrimonio-detail')
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
