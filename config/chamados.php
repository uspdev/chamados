<?php

$filas = [
    'triagem' => 0, # 0: sem triagem, 1: triagem pelo gerente da fila
    'visibilidade' => [
        'alunos' => 0,
        'servidores' => 1,
        'setores' => 'todos', # todos ou interno
    ],
    'patrimonio' => 0,
];

return [
    'admins' => env('ADMINS'),
    'usar_replicado' => env('USAR_REPLICADO', true),
    'upload_max_filesize' => env('UPLOAD_MAX_FILESIZE', '16M') * 1024,
    'filas' => $filas,
];
