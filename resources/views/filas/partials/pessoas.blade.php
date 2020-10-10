<div class="font-weight-bold">
    Pessoas

    @include('filas.partials.pessoas-btn-add-modal', [
        'modalTitle' => 'Adicionar pessoas',
        'url'=>$data->url.'/'.$data->row->id.'/pessoas',
    ])

</div>
<?php 
#dd($data->row->user);
?>
<ul class="list-unstyled ml-2">
    @foreach($data->row->user as $user)
    <li class="form-inline">
        {{ $user->name }} - {{ $user->pivot->funcao }}
        @include('common.btn-delete-sm', ['action'=>'filas/'.$data->row['id'].'/pessoas/'.$user->id])
    </li>
    @endforeach
</ul>
