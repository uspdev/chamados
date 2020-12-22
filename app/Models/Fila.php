<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    # valor default
    protected $attributes = [
        'estado' => 'Em elaboração',
    ];

    protected $fillable = [
        'nome',
        'descricao',
        'template',
        'setor_id',
        'estado',
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
            'label' => 'Descrição',
        ],
    ];

    public static function getFields()
    {
        $fields = SELF::fields;
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

    public static function getPessoaModel()
    {
        return [
            [
                'name' => 'nome',
                'label' => 'Nome',
            ],
            [
                'name' => 'funcao',
                'label' => 'Função',
                'type' => 'select',
                'options' => ['Gerente', 'Atendente'],
                'data' => [],
            ],
        ];
    }

    public static function getTemplateFields()
    {
        return ['label', 'type', 'can', 'help', 'value', 'validate'];
    }

    public static function estados()
    {
        return ['Em elaboração', 'Em produção', 'Desativada'];
    }

    /**
     * Accessor getter para $config
     */
    public function getConfigAttribute($value)
    {
        $value = json_decode($value);

        $v = new \StdClass;
        $v->alunos = isset($value->visibilidade->alunos) ? $value->visibilidade->alunos : config('filas.config.visibilidade.alunos');
        $v->servidores = isset($value->visibilidade->servidores) ? $value->visibilidade->servidores : config('filas.config.visibilidade.servidores');
        $v->gerentes = isset($value->visibilidade->gerentes) ? $value->visibilidade->gerentes : config('filas.config.visibilidade.gerentes');
        $v->setores = isset($value->visibilidade->setores) ? $value->visibilidade->setores : config('filas.config.visibilidade.setores');

        $out = new \StdClass;
        $out->triagem = $value->triagem ?? config('filas.config.triagem');
        $out->patrimonio = $value->patrimonio ?? config('filas.config.patrimonio');
        $out->visibilidade = $v;

        return $out;
    }

    /**
     * Accessor setter para $config
     */
    public function setConfigAttribute($value)
    {
        $v = new \StdClass;
        $v->alunos = $value['visibilidade']['alunos'] ?? 0;
        $v->servidores = $value['visibilidade']['servidores'] ?? 0;
        $v->gerentes = $value['visibilidade']['gerentes'] ?? 0;
        $v->setores = $value['visibilidade']['setores'];

        $config = new \StdClass;
        $config->triagem = $value['triagem'];
        $config->patrimonio = $value['patrimonio'];
        $config->visibilidade = $v;

        $this->attributes['config'] = json_encode($config);
    }

    /**
     * Accessor para $template
     */
    public function getTemplateAttribute($value)
    {
        return (empty($value)) ? '{}' : $value;
    }

    public static function listarFilas()
    {
        $user = \Auth()->user();

        # listando tudo se admin
        if ($user->is_admin) {
            return SELF::get();
        }

        $filas = collect();

        # listando as filas de todos os setores que o usuário faz parte.
        foreach (\Auth()->user()->setores as $setor) {
            $filas = $filas->merge($setor->filas);

            #listando as filas do setores filhos do usuario
            # somente 1 nível por enquanto
            foreach ($setor->setores as $setor_filho) {
                $filas = $filas->merge($setor_filho->filas);
            }
        }

        # listando as filas que o user é gerente
        $filas = $filas->merge($user->filas()->wherePivot('funcao', 'Gerente')->get());

        return $filas->unique('id');
    }

    /**
     * Mostra lista de setores e respectivas filas
     * para selecionar e criar novo chamado
     *
     * @return \Illuminate\Http\Response
     */
    public static function listarFilasParaNovoChamado()
    {
        # primeiro vamos pegar todas as filas
        $setores = Setor::get();

        # e depois filtrar as que não pode
        foreach ($setores as &$setor) {
            # primeiro vamos pegar todas as filas
            $filas = $setor->filas;

            # pegando somente as filas em produção
            $filas = $filas->filter(function ($fila, $key) {
                return $fila->estado == 'Em produção';
            });

            # filtrando por visibilidade de setor
            $filas = $filas->filter(function ($fila, $key) {
                if ($fila->config->visibilidade->setores == 'interno') {
                    return \Auth::user()->setores
                        ->where('sigla', $fila->setor->sigla)
                        ->first();
                } else {
                    // setores == 'todos'
                    return true;
                }
            });

            #filtrando servidores e gerentes
            $filas = $filas->filter(function ($fila, $key) {

                # primeiro servidores
                $servidor = \Auth::user()->setores()
                    ->wherePivot('funcao', 'Servidor')
                    ->first();
                if ($fila->config->visibilidade->servidores && $servidor) {
                    return true;
                }

                # depois filtrando gerentes
                $gerente = \Auth::user()->setores()
                    ->wherePivot('funcao', 'Gerente')
                    ->first();
                if ($fila->config->visibilidade->gerentes && $gerente) {
                    return true;
                }

                return false;
            });

            $setor->filas = $filas;
        }

        return $setores;
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
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_fila')
            ->withPivot('funcao')->orderBy('user_fila.funcao')->orderBy('users.name')
            ->withTimestamps();
    }

    /**
     * pivot da tabela user_fila
     */
    public static function userFuncoes()
    {
        return ['Gerente', 'Atendente'];
    }

    /**
     * Relacionamento: chamado pertence a fila
     */
    public function chamados()
    {
        return $this->hasMany(Chamado::class);
    }
}
