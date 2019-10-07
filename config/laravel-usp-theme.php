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
            'url'  => '/item1',
        ],
        [
            'text' => 'Triagem',
            'url'  => '/item2',
            'can'  => '',
        ],
        [
            'text' => 'Fila para atendimento',
            'url'  => '/item3',
            'can'  => '',
        ],
        [
            'text' => 'Categorias',
            'url'  => '/categorias',
            'can'  => '',
        ],
    ]
];
