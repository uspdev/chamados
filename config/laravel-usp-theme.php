<?php
# menu principal
$menu = [
    [
        'text' => '<i class="far fa-plus-square"></i> Novo Chamado',
        'url' => 'chamados/create',
        'can' => 'chamados.create',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Meus Chamados',
        'url' => 'chamados',
        'can' => 'perfilusuario',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Meus Atendimentos',
        'url' => 'chamados',
        'can' => 'perfilatendente',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Chamados Admin',
        'url' => 'chamados',
        'can' => 'perfiladmin',
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
    [
        'text' => '<span class="text-danger"><i class="fas fa-users ml-2"></i> Pessoas</span>',
        'url' => 'users',
        'can' => 'users.viewAny',
    ],
    [
        'text' => '<span class="text-danger"><i class="fas fa-tools ml-2"></i> Admin</span>',
        'url' => 'admin',
        'can' => 'perfiladmin',
    ],];

# para menu direito
$admin = [
    [
        'type' => 'header',
        'text' => '<b><i class="fas fa-cogs"></i>  Configurações</b>',
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

$right_menu = [
    [
        'text' => '<span class="badge badge-danger">Admin</span>',
        'url' => '',
        'can' => 'perfiladmin',
    ],   
    [
        'text' => '<span class="badge badge-danger">Desassumir identidade</span>',
        'url' => 'users/desassumir',
        'can' => 'desassumir',
    ],
    [
        'text' => '<span class="badge badge-warning">Atendente</span>',
        'url' => '',
        'can' => 'perfilatendente',
    ],
    [
        'text' => '<i class="fas fa-question-circle"></i> Ajuda',
        'url' => 'ajuda',
    ],
    [
        'text' => '<i class="fas fa-cog"></i>',
        'title' => 'Configurações',
        'submenu' => array_merge($admin, $trocarPerfil),
        'align' => 'right',
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
