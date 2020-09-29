<?php
if (substr($col['name'],-3) == '_id') {
    $table = substr($col['name'],0,-3);
    // sigla tem de ser substituido por algo que 
    // identifique a coluna a ser exibida
    echo ($row->$table) ? $row->$table->sigla : '';
} else {
    echo $row->{$col['name']};
}
?>
