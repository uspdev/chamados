<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'template',
        'setores_id',
    ];

    const rules = array(
        'nome' => ['required','max:90'],
        'descricao' => ['max:255'],
        'template' => [],
        'setores_id' => 'required|numeric',
    );

    const fields = [
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'descricao',
            'label' => 'Descricao',
        ],
        [
            'name' => 'setores_id',
            'model' => 'Setor',
            'label' => 'Setor',
            'type' => 'select',
            'data' => [],
        ],
    ];

    public static function getFields() {
        $fields = SELF::fields;
        //return $fields;
        foreach ($fields as &$field) {
            if (substr($field['name'],-3) == '_id') {
                $class= '\\App\\Models\\'.$field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    public function setores()
    {
        return $this->belongsTo('App\Models\Setor');
    }
}
