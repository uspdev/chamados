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
        @includewhen(Gate::check('update',$chamado), 'patrimonios.partials.patrimonio-add-modal')
    </div>
    <div class="card-body">
        @foreach ($chamado->patrimonios as $patrimonio)
        <div class="patrimonio-item form-inline">
            <b>{{ $patrimonio->numFormatado() }}</b>

            @if (config('chamados.usar_replicado') == 'true')
            : {{ $patrimonio->replicado()->epfmarpat ?? '' }} | {{ $patrimonio->replicado()->tippat ?? '' }} | {{ $patrimonio->replicado()->modpat ?? '' }}
            @include('patrimonios.show.patrimonio-detail', ['patrimonio'=>$patrimonio])
            @endif

            <span class="hidden-btn d-none">
                @includewhen(Gate::check('update',$chamado), 'common.btn-delete-sm', ['action'=>'chamados/'.$chamado->id.'/patrimonios/'.$patrimonio->id])
            </span>
        </div>
        @endforeach
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(function() {
        $('.patrimonio-item').hover(
            function() {
                $(this).find('.hidden-btn').removeClass('d-none');
            }
            , function() {
                $(this).find('.hidden-btn').addClass('d-none');
            }
        )
    });

</script>
@endsection
