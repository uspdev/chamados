<?php
$triagem_id = 'triagem-detail-'.Str::random(5);
?>

<a class="btn btn-sm btn-light text-primary" data-toggle="collapse" href="#{{ $triagem_id }}" role="button">
    <i class="fas fa-bars"></i>
</a>

<div class="collapse" id="{{ $triagem_id }}">
    <div class="card card-body">
        <div>
            <i class="fas fa-user mr-2"></i> {{ $user->name }}

        </div>
        <div>
            <i class="fas fa-envelope-square mr-2"></i> <a href="{{ $user->email }}">{{ $user->email }}</a>
        </div>
        <div>
            <i class="fas fa-phone mr-2"></i> {{ $user->telefone ?? 'não disponível' }}
        </div>
        <div>
        </div>
    </div>
</div>
