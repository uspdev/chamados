<?php

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
            'text' => 'Triagem',
            'url' => 'triagem',
            'can' => 'admin',
        ],
        [
            'text' => 'Atender',
            'url' => 'atender',
            'can' => 'atendente',
        ],
        [
            'text' => 'Todos Chamados',
            'url' => 'todos',
            'can' => 'atendente',
        ],
        [
            'text' => 'Por Id',
            'url' => 'buscaid',
            'can' => 'atendente',
        ],
        [
            'text' => 'Categorias',
            'url' => 'categorias',
            'can' => 'admin',
        ],
    ],
    'right_menu' => [
        [
            'text' => '<i class="fas fa-cog"></i> Configurações',
            'title' => 'Configurações',
            'can' => 'admin',
            'submenu' => [
                [
                    'text' => '<i class="fas fa-sitemap"></i> Setores',
                    'url' => 'setores',
                    'can' => 'admin',
                ],
                [
                    'text' => '<i class="fas fa-tasks"></i> Filas',
                    'url' => 'filas',
                    'can' => 'admin',
                ], 
                [
                    'text' => '<i class="fas fa-users"></i> Usuários',
                    'url' => 'users',
                    'can' => 'admin',
                ],
            ],

        ],
    ],
];
