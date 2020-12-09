<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Setor extends Model
{
    use HasFactory;

    # setores não segue convenção do laravel para nomes de tabela
    protected $table = 'setores';

    protected $fillable = [
        'sigla',
        'nome',
        'setor_id',
        'cod_set_replicado',
        'cod_set_pai_replicado',
    ];

    public const rules = [
        'sigla' => ['required', 'max:15'],
        'nome' => ['required', 'max:255'],
        'setor_id' => '',
        'cod_set_replicado' => '',
        'cod_set_pai_replicado' => '',
    ];

    # funcoes é pivot do relacionamento com users
    public const funcoes = ['Gerente', 'Colaborador'];

    protected const fields = [
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
            'data' => [],
        ],
    ];

    /**
     * utilizado nas views common
     */
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

    /**
     * retorna todos os setores autorizados para o usuário
     * utilizado nas views common, para o select
     */
    public static function allToSelect()
    {
        $setores = SELF::get();
        $ret = [];
        foreach ($setores as $setor) {
            if (Gate::allows('setores.view', $setor)) {
            $ret[$setor->id] = $setor->sigla . ' - ' . $setor->nome;
            }
        }
        return $ret;
    }

    public static function getDefaultColumn()
    {
        return 'sigla';
    }

    public static function vincularPessoa($setor, $user, $funcao)
    {
        $setor->users()->wherePivot('funcao', $funcao)->detach($user);
        $user->setores()->attach($setor, ['funcao' => $funcao]);
    }

    /**
     * Auto relacionamento
     */
    public function setor()
    {
        return $this->belongsTo('App\Models\Setor');
    }

    /**
     * Auto relacionamento reverso
     */
    public function setores()
    {
        return $this->hasMany('App\Models\Setor')->orderBy('sigla');
    }

    /**
     * Setor possui filas
     */
    public function filas()
    {
        return $this->hasMany('App\Models\Fila');
    }

    /**
     * Setor possui pessoas
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_setor')
            ->withPivot('funcao')->withTimestamps();
    }
}
