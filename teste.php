<?php

require_once 'vendor/autoload.php';

use Uspdev\Replicado\Pessoa;
    
# Obrigatórias
putenv('REPLICADO_HOST=143.107.182.10');
putenv('REPLICADO_PORT=1433');
putenv('REPLICADO_DATABASE=replicacao');
putenv('REPLICADO_USERNAME=masaki');
putenv('REPLICADO_PASSWORD=G3n8s2J6w4');
putenv('REPLICADO_CODUNDCLG=18');

# Opicionais
putenv('REPLICADO_SYBASE=1');

$emails = Pessoa::dump('7098274');
print_r($emails);