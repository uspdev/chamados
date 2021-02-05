<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    # valor default
    # passando um template com select até que seja feito uma melhoria na tela do formulário. Assim é só mudar o texto do json.
    protected $attributes = [
        'estado' => 'Em elaboração',
        'template' => '{
                            "complexidade": {
                                "label": "Complexidade",
                                "type": "select",
                                "value": {
                                    "baixa": "Baixa",
                                    "media": "Média",
                                    "alta": "Alta"
                                }
                            },
                            "prioridade": {
                                "label": "Prioridade",
                                "type": "select",
                                "value": {
                                    "baixa": "Baixa",
                                    "media": "Média",
                                    "alta": "Alta"
                                }
                            },
                            "tipo": {
                                "label": "Tipo de problema",
                                "type": "select",
                                "value": {
                                    "telefonia": "Problemas com telefone",
                                    "impressora": "Problemas com impressora",
                                    "software": "Instalação de software",
                                    "virus": "Computador com vírus",
                                    "site": "Não consigo atualizar o site do meu setor",
                                    "outro": "Não sei classificar meu problema"
                                }
                            }
                        }',
        'config' => '{
                "triagem":"0",
                "patrimonio":"0",
                "visibilidade":{
                    "alunos":0,
                    "servidores":"1",
                    "setor_gerentes":0,
                    "fila_gerentes":0,
                    "setores":"todos"
                },
                "status":[
                    {
                        "label":"Aguardando Solicitante",
                        "color":"danger"
                    },
                    {
                        "label":"Aguardando Peças",
                        "color":"info"
                    },
                    {
                        "label":"Cancelado",
                        "color":"dark"
                    },
                    {
                        "label":"Em Espera",
                        "color":"primary"
                    }
                ]
            }'
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

    public function getStatusToSelect()
    {
        $status = $this->config->status;
        if ($status) {
            $out = [];
            foreach ($status as $item) {
                foreach ($item as $key => $value) {
                    if ($key == "label") {
                        $out[strtolower($value)] = $value;
                    }
                }
            }
            return $out;
        }
    }

    public function getColortoLabel($chamado_status)
    {
        $status = $this->config->status;
        if ($status) {
            foreach ($status as $item) {
                if (strtolower($item->label) == $chamado_status) {
                    return $item->color;
                }
            }
        }
    }

    /**
     * Accessor getter para $config
     */
    public function getConfigAttribute($value)
    {
        $value = json_decode($value);

        #dd(config('filas.config.visibilidade'));
        $v = new \StdClass;
        foreach (config('filas.config.visibilidade') as $key => $val) {
            $v->$key = isset($value->visibilidade->$key) ? $value->visibilidade->$key : $val;
        }

        $out = new \StdClass;
        $out->triagem = $value->triagem ?? config('filas.config.triagem');
        $out->patrimonio = $value->patrimonio ?? config('filas.config.patrimonio');
        $out->visibilidade = $v;
        $out->status = $value->status ?? config('filas.config.status');
        return $out;
    }

    /**
     * Accessor setter para $config
     */
    public function setConfigAttribute($value)
    {
        $v = new \StdClass;
        foreach (config('filas.config.visibilidade') as $key => $val) {
            $v->$key = $value['visibilidade'][$key] ?? 0;
        }

        $config = new \StdClass;
        $config->triagem = $value['triagem'];
        $config->patrimonio = $value['patrimonio'];
        $config->visibilidade = $v;

        $status = [];
        for ($i = 0; $i < count($value['status']['select']); $i++) {
            $s = new \StdClass;
            $s->label = $value['status']['select'][$i];
            $s->color = $value['status']['select_cor'][$i];
            array_push($status, $s);
        }
        $config->status = $status;

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
            // $filas = $filas->filter(function ($fila, $key) {
            //     return $fila->estado == 'Em produção';
            // });

            # filtrando por visibilidade de setor
            // $filas = $filas->filter(function ($fila, $key) {
            //     if ($fila->config->visibilidade->setores == 'interno') {
            //         return \Auth::user()->setores
            //             ->where('sigla', $fila->setor->sigla)
            //             ->first();
            //     } else {
            //         // setores == 'todos'
            //         return true;
            //     }
            // });

            # agora vamos remover as filas onde não se pode abrir chamados
            $filas = $filas->filter(function ($fila, $key) {

                # bloqueia as filas que não estão em produção
                if ($fila->estado != 'Em produção') {
                    return false;
                }

                # liberando pessoas (gerentes, servidores, etc) do proprio setor (interno)
                $interno = \Auth::user()->setores->contains($fila->setor);
                if ($fila->config->visibilidade->setores == 'interno' && $interno) {
                    return true;
                }

                # liberando as filas que o usuário participa (gerente e atendente)
                if (\Auth::user()->filas->contains($fila)) {
                    return true;
                }

                # liberando todos os servidores
                $servidor = \Auth::user()->setores()->wherePivot('funcao', 'Servidor')->first();
                if ($fila->config->visibilidade->servidores && $servidor) {
                    return true;
                }

                # liberando gerentes de todos os setores
                $gerente_setor = \Auth::user()->setores()->wherePivot('funcao', 'Gerente')->first();
                if ($fila->config->visibilidade->setor_gerentes && $gerente_setor) {
                    return true;
                }

                # liberando gerentes de todas as filas
                $gerente_fila = \Auth::user()->filas()->wherePivot('funcao', 'Gerente')->first();
                if ($fila->config->visibilidade->fila_gerentes && $gerente_fila) {
                    return true;
                }

                # se não houver nenhuma liberação então bloqueia a fila
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
