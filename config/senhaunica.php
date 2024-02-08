<?php

return [
    // para rotas internas
    'routes' => true, // usa rotas e controller internos

    // coloque um prefixo em caso de colisão de rotas
    // para todas as rotas internas da biblioteca (login, loginas, callback, logout e users).
    'prefix' => '',

    'middleware' => ['web'], // you probably want to include 'web' here

    // chave da sessão. Troque em caso de colisão com outra variável de sessão
    'session_key' => 'senhaunica-socialite',

    // -----------------------------------------------------
    // Para views internas da biblioteca (users)

    // template a ser estendido. Deve possuir a section "content"
    'template' => 'laravel-usp-theme::master',

    // define as rotas para o gerenciador de usuários interno, dentro de prefix
    // se vazio, desabilita a rota de gerenciamento de usuários interna
    'userRoutes' => 'senhaunica-users',

    // se true, habilita botão para remover usuário (destroy)
    'destroyUser' => false,

    // view para editar campo de usuário personalizado. Pode ser mais de uma coluna
    // 'key' é opcional e se estiver setado permite ordenar por esta coluna, passando o nome da coluna do DB
    // é passado $user para a view
    'customUserField' => [
        ['view' => 'users.partials.cuf-ultimo-login', 'key' => 'last_login_at', 'label' => 'Último login', 'width' => ''],
        ['view' => 'users.partials.cuf-perfil', 'label' => '', 'width' => ''],
    ],

    // Define o gate para a rota de busca de pessoas
    'findUsersGate' => 'admin',

    // fim views internas
    // -----------------------------------------------------

    // usa as permissoes internas, padrão para v4.
    // Se false, não usará permission ao efetuar login
    'permission' => false,

    // permite login somente de usuários já cadastrados na base local ou autorizados nos admins, gerentes ou users
    'onlyLocalUsers' => false,

    // se true, revoga as permissões do usuario se não estiver no env.
    // quer dizer que as permissões serão gerenciadas todas a partir do env da aplicação.
    'dropPermissions' => env('SENHAUNICA_DROP_PERMISSIONS', false),

    // cadastre os admins separados por virgula
    'admins' => array_map('trim', explode(',', env('SENHAUNICA_ADMINS', ''))),

    // cadastre os gerentes separados por virgula
    'gerentes' => array_map('trim', explode(',', env('SENHAUNICA_GERENTES', ''))),

    // se quiser cadastre os usuários comuns autorizados. Relevante se onlyLocalUsers = true
    'users' => array_map('trim', explode(',', env('SENHAUNICA_USERS', ''))),

    // se true, ele grava no filesystem o retorno json do servidor oauth
    'debug' => (bool) env('SENHAUNICA_DEBUG', false),

    'dev' => env('SENHAUNICA_DEV', 'no'),
    'callback_id' => env('SENHAUNICA_CALLBACK_ID'),

    // codigo da unidade para identificar logins proprios
    // relevante se permission=true
    'codigoUnidade' => env('SENHAUNICA_CODIGO_UNIDADE'),

    // SENHAUNICA_KEY e SENHAUNICA_SECRET são carregados em services.php da biblioteca
];
