@section('styles')
@parent
<style>
    #card-vinculados {
        border: 1px solid chocolate;
        border-top: 3px solid chocolate;
    }

</style>
@endsection

<a name="card_vinculados"></a>
<div class="card bg-light mb-3" id="card-vinculados">
    <div class="card-header">
        Chamados Vinculados
        @includewhen(Gate::check('update',$chamado), 'chamados.show.vinculados-add-modal')
        <span title="Você pode permitir o acesso a outros chamados vinculando-os a este." class="ajuda" data-toggle="tooltip">
            <i class="fas fa-question-circle text-secondary"></i>
        </span>
    </div>
    <div class="card-body">

        <ul class="ml-2 list-unstyled lista-vinculados">
            @forelse($chamado->vinculados as $vinculado)
            <li class="form-inline">
                <a href="chamados/{{$vinculado->id}}">
                    {{ $vinculado->nro }}/{{ $vinculado->created_at->format('Y') }}
                    {{ Illuminate\Support\Str::limit($vinculado->assunto, 30, '...') }}
                </a>
                <span class="hidden-btn d-none">
                    @includewhen(Gate::check('update', $chamado), 'common.btn-delete-sm', ['action'=>'chamados/'.$chamado->id.'/vinculado/'.$vinculado->id])
                </span>
            </li>
            @empty
            Sem chamados vinculados
            @endforelse
        </ul>
    </div>
</div>

@section('javascripts_bottom')
@parent
<script>
    $(function() {
        $('.lista-vinculados li').hover(
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
