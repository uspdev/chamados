<?php
$user_detail_id = 'iser-detail-' . Str::random(5);
?>

<a class="btn btn-sm btn-light text-primary py-0" data-toggle="collapse" href="#{{ $user_detail_id }}" role="button" aria-expanded="false" aria-controls="collapseExample">
    <i class="fas fa-user"></i>
</a>

<div class="collapse" id="{{ $user_detail_id }}">
    <div class="card card-body">
        <span class="text-dark">
            <div>
                {{ $user->codpes }} - {{ $user->name }}
            </div>
            <div>
                <i class="fas fa-envelope-square mr-2"></i> <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
            </div>
            <div>
                <i class="fas fa-phone mr-2"></i> {{ $user->telefone ?? 'não disponível' }}
            </div>
        </span>
    </div>
</div>