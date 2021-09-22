<?php

return [
    // para rotas internas
    'routes' => false, // usa rotas e controller internos

    // coloque um prefixo em caso de colisão de rotas
    // para todas as rotas internas da biblioteca (login, loginas, callback, logout e users).
    'prefix' => '',

    'middleware' => ['web'], // you probably want to include 'web' here
    'session_key' => 'senhaunica-socialite', // chave da sessão. Troque em caso de colisão com outra variável de sessão.
    'template' => 'laravel-usp-theme::master', // template a ser estendido para as views internas, deve possuir a section "content"

    // define as rotas para o gerenciador de usuários interno, dentro de prefix
    // se vazio, desabilita a rota de gerenciamento de usuários interna
    'userRoutes' => 'users',

    // usa as permissoes internas, padrão para v4.
    // Se false, não usará permission ao efetuar login
    'permission' => false,

    // permite login somente de usuários já cadastrados na base local ou autorizados nos admins, gerentes ou users
    'onlyLocalUsers' => false,

    // se true, habilita botão para remover usuário (destroy)
    'destroyUser' => false,

    // se true, revoga as permissões do usuario se não estiver no env.
    // quer dizer que as permissões serão gerenciadas todas a partir do env da aplicação.
    'dropPermissions' => env('SENHAUNICA_DROP_PERMISSIONS', false),

    // cadastre os admins separados por virgula
    'admins' => array_map('trim', explode(',', env('SENHAUNICA_ADMINS', ''))),

    // cadastre os gerentes separados por virgula
    'gerentes' => array_map('trim', explode(',', env('SENHAUNICA_GERENTES', ''))),

    // se quiser cadastre os usuários comuns autorizados. Relevante se onlyLocalUsers = true
    'users' => array_map('trim', explode(',', env('SENHAUNICA_USERS', ''))),

    'dev' => env('SENHAUNICA_DEV', 'no'),
    'debug' => (bool) env('SENHAUNICA_DEBUG', false),
    'callback_id' => env('SENHAUNICA_CALLBACK_ID'),

    // SENHAUNICA_KEY e SENHAUNICA_SECRET são carregados em services.php da biblioteca
];
