<?php

$filas = [
    [
        'text' => 'Filas',
        'url' => 'filas',
        'can' => 'admin',
    ],
    [
        'text' => 'Nova Fila',
        'url' => 'filas/create',
        'can' => 'admin',
    ],
];

$admin = [
    [
        'type' => 'header',
        'text' => '<b><i class="fas fa-cogs"></i>  Configurações</b>',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-sitemap ml-2"></i> Setores',
        'url' => 'setores',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-tasks ml-2"></i> Filas',
        'url' => 'filas',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-users ml-2"></i> Pessoas',
        'url' => 'users',
        'can' => 'admin',
    ],
];

$trocarPerfil = [
    [
        'type' => 'divider',
        'can' => 'trocarPerfil',
    ],
    [
        'type' => 'header',
        'text' => '<b><i class="fas fa-id-badge"></i>  Trocar perfil</b>',
        'can' => 'trocarPerfil',
    ],
    [
        'text' => '&nbsp; Admin',
        'url' => 'users/perfil/admin',
        'can' => 'admin',
    ],
    [
        'text' => '&nbsp; Atendente',
        'url' => 'users/perfil/atendente',
        'can' => 'atendente',
    ],
    [
        'text' => '&nbsp; Usuário',
        'url' => 'users/perfil/usuario',
        'can' => 'trocarPerfil',
    ],
];
$configuracoes = array_merge($admin,$trocarPerfil);

return [
    'title' => env('APP_NAME'),
    'dashboard_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => [
        [
            'text' => 'Novo Chamado',
            'url' => 'chamados/create',
            'can' => 'chamados.create',
        ],
        [
            'text' => 'Meus Chamados',
            'url' => 'chamados',
            'can' => 'chamados.create',
        ],
        [
            'text' => 'Filas',
            'url' => '',
            'can' => 'admin',
            'submenu' => $filas,
        ],
    ],
    'right_menu' => [
        [
            'text' => '<span class="badge badge-danger">Admin</span>',
            'url' => '',
            'can' => 'perfilAdmin',
        ],
        [
            'text' => '<span class="badge badge-warning">Atendente</span>',
            'url' => '',
            'can' => 'perfilAtendente',
        ],
        [
            'text' => '<i class="fas fa-question-circle"></i> Ajuda',
            'url' => 'ajuda',
        ],
        [
            'text' => '<i class="fas fa-cog"></i>',
            'title' => 'Configurações',
            'submenu' => $configuracoes,
            'align' => 'right',
            'can' => 'atendente',
        ],
    ],
];
