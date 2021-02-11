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
        'can' => 'menuConfiguracoes',
    ],
    [
        'text' => '<i class="fas fa-users ml-2"></i> Pessoas',
        'url' => 'users',
        'can' => 'admin',
    ],
    [
        'text' => '<i class="fas fa-users ml-2"></i> Meu Perfil',
        'url' => 'users/meuperfil',
        'can' => 'usuario',
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
$configuracoes = array_merge($admin, $trocarPerfil);

$menu = [
    [
        'text' => '<i class="far fa-plus-square"></i> Novo Chamado',
        'url' => 'chamados/create',
        'can' => 'chamados.create',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Meus Chamados',
        'url' => 'chamados',
        'can' => 'chamados.viewAny',
    ],
    [
        'text' => '<i class="fas fa-sitemap ml-2"></i> Setores',
        'url' => 'setores',
        'can' => 'setores.viewAny',
    ],
    [
        'text' => '<i class="fas fa-tasks ml-2"></i> Filas',
        'url' => 'filas',
        'can' => 'filas.viewAny',
    ],
];

$right_menu = [
    [
        'text' => '<span class="badge badge-danger">Admin</span>',
        'url' => '',
        'can' => 'perfilAdmin',
    ],   
    [
        'text' => '<span class="badge badge-danger">Desassumir identidade</span>',
        'url' => 'users/desassumir',
        'can' => 'desassumir',
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
        #'can' => ['perfilAtendente','filas.viewAny'],
    ],
];

return [
    'title' => config('app.name'),
    'skin' => env('USP_THEME_SKIN', 'uspdev'),
    'app_url' => config('app.url'),
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => $menu,
    'right_menu' => $right_menu,
];
