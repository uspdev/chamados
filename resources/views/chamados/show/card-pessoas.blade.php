@section('styles')
@parent
<style>
    #card-pessoas {
        border: 1px solid brown;
        border-top: 3px solid brown;
    }
</style>
@endsection

<a name="card_pessoas"></a>
<div class="card bg-light mb-3" id="card-pessoas">
    <div class="card-header">
        Pessoas
        <span class="badge badge-pill badge-primary">{{ $chamado->users->count() }}</span>
        @can('update',$chamado)
        @include('chamados.show.adicionar-pessoa-btn')
        @endcan
    </div>
    <div class="card-body">
        <ul class="ml-2 list-unstyled lista-pessoas">
            @foreach($chamado->users as $user)
            <li class="form-inline">
                <span class="
                    @switch($user->pivot->papel)
                    @case('Autor') text-success @break
                    @case('Atendente') text-danger @break
                    @endswitch
                ">
                    {{ $user->name }} ({{ $user->pivot->papel}}) 
                </span>
                <span class="hidden-btn d-none">
                    @include('chamados.show.user-detail', ['user'=>$user])
                </span>
                <span class="hidden-btn d-none">
                    @switch($user->pivot->papel)
                    @case('Autor') @case('Atendente') {{-- libera delete para atendente e admin --}}
                    @if (Gate::allows('perfilAtendente') or Gate::allows('perfilAdmin'))
                    @include('common.btn-delete-sm', ['action'=>'chamados/'.$chamado->id.'/pessoas/'.$user->id])
                    @endif
                    @break
                    @case('Observador') {{-- libera delete para todos --}}
                    @include('common.btn-delete-sm', ['action'=>'chamados/'.$chamado->id.'/pessoas/'.$user->id])
                    @break
                    @endswitch
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
        $('.lista-pessoas li').hover(
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