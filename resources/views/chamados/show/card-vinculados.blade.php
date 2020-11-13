<div class="card bg-light mb-3">
    <div class="card-header h5">
        Chamados Vinculados
        @can('update',$chamado)
        @include('chamados.show.vinculados-add-modal')
        @endcan
    </div>
    <div class="card-body">

        <ul class="ml-2 list-unstyled lista-vinculados">
            @forelse($vinculados as $vinculado)
            <li class="form-inline">
                <a href="chamados/{{$vinculado->id}}">
                    {{ $vinculado->nro }}/{{ Carbon\Carbon::parse($vinculado->created_at)->format('Y') }}
                    {{ Illuminate\Support\Str::limit($vinculado->assunto, 30, '...') }}
                </a>
                <span class="hidden-btn d-none">
                    @include('common.btn-delete-sm', ['action'=>'chamados/'.$chamado->nro.'/vinculado/'.$vinculado->nro])
                </span>
            </li>
            @empty
            nenhum
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
            },
            function() {
                $(this).find('.hidden-btn').addClass('d-none');
            }
        )
    });
</script>
@endsection