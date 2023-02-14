<?php
# menu principal
$menu = [
    [
        'text' => '<i class="far fa-plus-square"></i> Novo Chamado',
        'url' => 'chamados/create',
        'can' => 'usuario',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Meus Chamados',
        'url' => 'chamados?perfil=usuario',
        'can' => 'usuario',
    ],
    [
        'text' => '<i class="far fa-list-alt"></i> Meus Atendimentos',
        'url' => 'chamados?perfil=atendente',
        'can' => 'atendente',
    ],
    [
        'text' => '<span class="text-danger"><i class="far fa-list-alt"></i> Chamados Admin</span>',
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
    ]];

# para menu direito
$admin = [

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
        'key' => 'laravel-tools',
    ],
    // [
    //     // menu utilizado para views da biblioteca senhaunica-socialite.
    //     'key' => 'senhaunica-socialite',
    // ],
    [
        'text' => '<span class="badge badge-danger">Admin</span>',
        'url' => '#',
        'can' => 'perfiladmin',
    ],
    [
        'text' => '<span class="badge badge-danger">Desassumir identidade</span>',
        'url' => 'users/desassumir',
        'can' => 'desassumir',
    ],
    [
        'text' => '<span class="badge badge-warning">Atendente</span>',
        'url' => '#',
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
    # valor default para a tag title, dentro da section title.
    # valor pode ser substituido pela aplicação.
    'title' => config('app.name'),

    # USP_THEME_SKIN deve ser colocado no .env da aplicação
    'skin' => env('USP_THEME_SKIN', 'uspdev'),

    # chave da sessão. Troque em caso de colisão com outra variável de sessão.
    'session_key' => 'laravel-usp-theme',

    # usado na tag base, permite usar caminhos relativos nos menus e demais elementos html
    # na versão 1 era dashboard_url
    'app_url' => config('app.url'),

    # login e logout
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',

    # menus
    'menu' => $menu,
    'right_menu' => $right_menu,

    # mensagens flash - https://uspdev.github.io/laravel#31-mensagens-flash
    'mensagensFlash' => false,

    # container ou container-fluid
    'container' => 'container-fluid',
];
