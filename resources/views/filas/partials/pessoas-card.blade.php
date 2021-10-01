@section('styles')
@parent

<style>
    .lista-pessoas li:hover {
        background-color: SeaShell;
    }

    #card-fila-pessoas {
        border: 1px solid coral;
        border-top: 3px solid coral;
    }

</style>
@endsection

<div class="card mb-3" id="card-fila-pessoas">
    <div class="card-header">
        <i class="fas fa-users"></i> Pessoas
        @include('filas.partials.pessoas-btn-add-modal')
    </div>
    <div class="card-body">
        <ul class="list-unstyled ml-2 lista-pessoas">
            @foreach($fila->users as $user)
            <li class="form-inline">
                {{ $user->name }} - {{ $user->pivot->funcao }}
                <span class="hidden-btn d-none">
                    @include('common.btn-delete-sm', ['action'=>'filas/'.$fila->id.'/pessoas/'.$user->id])
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
            }
            , function() {
                $(this).find('.hidden-btn').addClass('d-none');
            }
        )
    });

</script>
@endsection
