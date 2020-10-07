<?php
// renderiza um campo no datables de listagem 

if (substr($col['name'],-3) == '_id') {
    // se for chave estrangeira
    $foreign = substr($col['name'],0,-3);
    $model = 'App\\Models\\'.ucfirst($foreign);
    $column = $model::getDefaultColumn();
    echo ($row->$foreign) ? $row->$foreign->$column : '-';
} 
elseif (isset($col['format']) && $col['format'] == 'timestamp' && !empty($row->{$col['name']})) {
    // se for timestamp
    echo date('d/m/Y H:i', strtotime($row->{$col['name']}));
}
elseif (isset($col['format']) && $col['format'] == 'boolean') {
    // sefor boolean
    if (!empty($row->{$col['name']})) {
        echo 'Sim';
    } else {
        //echo 'não';
    }
}else {
    // coluna normal
    echo $row->{$col['name']};
}
?>
