<?php

return [
    'title'=> env('APP_NAME'),
    'dashboard_url' => '/',
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => [
        [
            'text' => 'Novo Chamado',
            'url'  => '/chamados/create',
        ],
        [
            'text' => 'Meus Chamados',
            'url'  => '/chamados',
        ],
        [
            'text' => 'Triagem',
            'url'  => '/triagem',
            'can'  => 'admin',
        ],
        [
            'text' => 'Atender',
            'url'  => '/atender',
            'can'  => '',
        ],
        [
            'text' => 'Categorias',
            'url'  => '/categorias',
            'can'  => 'admin',
        ],
    ]
];
