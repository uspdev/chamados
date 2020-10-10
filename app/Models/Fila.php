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
        'setor_id',
    ];

    public const rules = [
        'nome' => ['required', 'max:90'],
        'descricao' => ['max:255'],
        'template' => [],
        'setor_id' => 'required|numeric',
    ];

    protected const fields = [
        [
            'name' => 'setor_id',
            'label' => 'Setor',
            'type' => 'select',
            'model' => 'Setor',
            'data' => [],
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'descricao',
            'label' => 'Descricao',
        ],

    ];

    public static function getFields()
    {
        $fields = SELF::fields;
        //return $fields;
        foreach ($fields as &$field) {
            if (substr($field['name'], -3) == '_id') {
                $class = '\\App\\Models\\' . $field['model'];
                $field['data'] = $class::allToSelect();
            }
        }
        return $fields;
    }

    public function getDefaultColumn()
    {
        return 'nome';
    }

    public static function getPessoaModel() {
        return [
            [
                'name' => 'nome',
                'label' => 'Nome',
            ],
            [
                'name' => 'funcao',
                'label' => 'Função',
                'type' => 'select',
                'options' => ['Gerente','Atendente'],
                'data' => [],
            ],
        ];
    }

    /**
     * Relacionamento: fila pertence a setor
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    /**
     * Relacionamento n:n com user, atributo funcao: Gerente, Atendente
     */
    public function user()
    {
        return $this->belongsToMany('App\Models\User', 'user_fila')
            ->withPivot('funcao')->orderBy('user_fila.funcao')->orderBy('users.name')
            ->withTimestamps();
    }

    /**
     * Relacionamento: chamado pertence a fila
     */
    public function chamados()
    {
        return $this->hasMany(Chamados::class);
    }
}
