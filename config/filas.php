<?php

$config = [
    'triagem' => 0, # 0: sem triagem, 1: triagem pelo gerente da fila
    'visibilidade' => [
        'alunos' => 0,
        'servidores' => 1,
        'todos' => 0,
        'setor_gerentes' => 0, # gerentes de setores
        'fila_gerentes' => 0, # gerentes de filas
        'setores' => 'todos', # todos ou interno
    ],
    'patrimonio' => 0,
    'editar_comentarios' => 0,
    'editar_comentarios_timeout_horas' => config('chamados.editar_comentarios_timeout_horas'),
];

$template = '{}';

return [
    'config' => $config,
    'template' => $template,
];
