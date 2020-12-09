<?php

return [
    'admins' => env('ADMINS'),
    'usar_replicado' => env('USAR_REPLICADO', true),
    'upload_max_filesize' => env('UPLOAD_MAX_FILESIZE', '16M')*1024,
];
