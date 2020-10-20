<div class="">
    <span class="font-weight-bold">Atendente</span>
    @can('admin')
        @if ($chamado->status != 'Fechado')
            |
            @include('chamados.partials.show-triagem-modal', ['modalTitle'=>'Triagem', 'url'=>'ok'])
        @endif
    @endcan<br>

    <div class="ml-2">
        @if($atendentes->count())
            @foreach($atendentes as $atendente)
                {{ $atendente->name }} @include('chamados.show.user-detail', ['user'=>$atendente])<br>
            @endforeach
            <span class="text-muted">Complexidade</span>: {{ $chamado->complexidade }}<br>
        @else
            Não atribuído
        @endif
    </div>
    <br>
</div>