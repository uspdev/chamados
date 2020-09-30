<?php
if (substr($col['name'],-3) == '_id') {
    $foreign = substr($col['name'],0,-3);
    $model = 'App\\Models\\'.ucfirst($foreign);
    $column = $model::getDefaultColumn();
    // sigla tem de ser substituido por algo que 
    // identifique a coluna a ser exibida
    echo ($row->$foreign) ? $row->$foreign->$column : '-';
} else {
    echo $row->{$col['name']};
}
?>
