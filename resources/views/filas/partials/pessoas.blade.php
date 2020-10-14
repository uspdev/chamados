<style>
    .lista-pessoas li:hover {
        background-color: SeaShell;
    }

</style>

<div class="font-weight-bold">
    Pessoas
    @include('filas.partials.pessoas-btn-add-modal')
</div>

<ul class="list-unstyled ml-2 lista-pessoas">
    @foreach($data->row->users as $user)
    <li class="form-inline">
        {{ $user->name }} - {{ $user->pivot->funcao }}
        <span class="hidden-btn d-none">
            @include('common.btn-delete-sm', ['action'=>'filas/'.$data->row['id'].'/pessoas/'.$user->id])
        </span>
    </li>
    @endforeach
</ul>

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
