<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;
    protected $table = 'setores';

    protected $fillable = [
        'sigla',
        'nome',
        'setor_id',
    ];

    const rules = array(
        'sigla' => ['required', 'max:15'],
        'nome' => ['required', 'max:255'],
        'setor_id' => '',
    );

    const fields = [
        [
            'name' => 'sigla',
            'label' => 'Sigla',
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'setor_id',
            'label' => 'Pai',
            'type' => 'select',
            'model' => 'Setor',
            'data' => []
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
    
    public static function allToSelect()
    {
        $rows = SELF::select('id', 'sigla')->get()->toArray();
        $ret = [];
        foreach ($rows as $row) {
            $ret[$row['id']] = $row['sigla'];
        }
        return $ret;
    }

    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    public static function getDefaultColumn()
    {
        return 'sigla';
    }
}
