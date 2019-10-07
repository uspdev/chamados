<?php

return [
    'title'=> 'USPdev',
    'dashboard_url' => '/',
    'logout_method' => 'GET',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'menu' => [
        [
            'text' => 'Item 1',
            'url'  => '/item1',
        ],
        [
            'text' => 'Item 2',
            'url'  => '/item2',
            'can'  => '',
        ],
        [
            'text' => 'Item 3',
            'url'  => '/item3',
            'can'  => 'admin',
        ],
    ]
];
