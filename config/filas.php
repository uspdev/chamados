<?php

$config = [
    'triagem' => 0, # 0: sem triagem, 1: triagem pelo gerente da fila
    'visibilidade' => [
        'alunos' => 0,
        'servidores' => 1,
        'setores' => 'todos', # todos ou interno
    ],
    'patrimonio' => 0,
];

$template = '{}';

return [
    'config' => $config,
    'template' => $template,
];
