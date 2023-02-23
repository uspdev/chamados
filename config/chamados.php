<?php

return [
    // Deprecado, remover no próximo release
    'admins' => env('ADMINS'),

    'usar_replicado' => env('USAR_REPLICADO', true),
    'upload_max_filesize' => (int) env('UPLOAD_MAX_FILESIZE', '16') * 1024,

    // deprecado em 2/23. Remover no próximo release
    'forcar_https' => env('FORCAR_HTTPS', false),

    'sistemaPatrimonio' => env('SISTEMA_PATRIMONIO', null),
];
