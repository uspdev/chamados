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
        @can('update', $chamado)
            @include('patrimonios.partials.patrimonio-add-modal')
        @endcan
    </div>
    <div class="card-body">
        <ul class="ml-2 list-unstyled lista-patrimonios">
            @foreach ($chamado->patrimonios as $patrimonio)
                <li class="form-inline">
                    <b>{{ $patrimonio->numFormatado() }}</b>
                    @if (config('chamados.usar_replicado') == 'true')
                        : {{ $patrimonio->replicado()->epfmarpat ?? '' }}
                        {{ $patrimonio->replicado()->tippat ?? '' }}
                        {{ $patrimonio->replicado()->modpat ?? '' }}
                    @endif
                    <span class="hidden-btn d-none">
                        @include('common.btn-delete-sm',
                        ['action'=>'chamados/'.$chamado->id.'/patrimonios/'.$patrimonio->id])
                    </span>
                    <span class="hidden-btn d-none">
                        @include('patrimonios.show.patrimonio-detail', ['patrimonio'=>$patrimonio])
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@section('javascripts_bottom')
    @parent
    <script>
        $(function() {
            $('.lista-patrimonios li').hover(
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
