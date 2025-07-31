<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Uspdev\Replicado\Estrutura;

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
        'setor_id' => 'integer',
        'cod_set_replicado' => 'nullable',
        'cod_set_pai_replicado' => 'nullable',
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
        $u = $setor->users()->where('user_id', $user->id)->wherePivot('funcao', $funcao)->withPivot('funcao')->first();

        // vamos cadastrar ou atualizar o setor somente se mudou de setor
        if (empty($u) || $u->pivot->funcao != $funcao) {
            config('app.debug') && Log::info("Atualizado setor de $user->codpes em $setor->sigla na função $funcao");
            $setor->users()->wherePivot('funcao', $funcao)->detach($user);
            $user->setores()->attach($setor, ['funcao' => $funcao]);
        }
    }

    /**
     * Sincroniza os setores com o replicado e retorna uma mensagem com as estatísticas
     *
     * Adiciona, remove e renomeia os setores.
     * Adiciona os chefes como gerentes dos setores.
     * Não remove os antigos pois se for substituição vai remover o chefe de fato.
     * Chamado no artisan setores:sync
     */
    public static function sincronizarComReplicado()
    {
        $estatisticas = [
            'setores_criados' => [],
            'setores_atualizados' => [],
            'chefes_adicionados' => [],
            'setores_removidos' => [],
        ];

        $setores_repl = Estrutura::listarSetores();
        $cod_unidade = config('replicado.codundclg');

        // Primeiro loop: cria ou atualiza setores
        foreach ($setores_repl as $setor_repl) {
            $setor = self::firstOrNew(['cod_set_replicado' => $setor_repl['codset']]);
            $is_novo = !$setor->exists;

            // Atualiza atributos
            $setor->sigla = str_replace('-' . $cod_unidade, '', $setor_repl['nomabvset']);
            $setor->nome = $setor_repl['nomset'];
            $setor->cod_set_replicado = $setor_repl['codset'];
            $setor->cod_set_pai_replicado = $setor_repl['codsetspe'];

            if ($is_novo) {
                $setor->save();
                $estatisticas['setores_criados'][] = $setor->nome;
            } elseif ($setor->isDirty()) {
                $setor->save();
                $estatisticas['setores_atualizados'][] = $setor->nome;
            }
        }

        // Segundo loop: associa setor pai e chefes
        foreach ($setores_repl as $setor_repl) {
            $setor = self::where('cod_set_replicado', $setor_repl['codset'])->first();

            $setor_pai = self::where('cod_set_replicado', $setor->cod_set_pai_replicado)->first();
            if ($setor_pai) {
                $setor->setor_id = $setor_pai->id;
                $setor->save();
            }

            if (!empty($setor->setor_id)) {
                $chefes = Estrutura::getChefiaSetor($setor_repl['codset']);
                foreach ($chefes as $chefe) {
                    $u = User::obterOuCriarPorCodpes($chefe['codpes']);

                    $attached = $setor->users()
                        ->wherePivot('user_id', $u->id)
                        ->wherePivot('funcao', 'Gerente')
                        ->exists();

                    if (!$attached) {
                        $setor->users()->attach($u->id, ['funcao' => 'Gerente']);
                        $estatisticas['chefes_adicionados'][] = "{$u->name} ({$setor->nome})";
                    }
                }
            }
        }

        // Terceiro loop: remove setores que não existem mais no replicado
        $codigos_replicado = array_column($setores_repl, 'codset');
        $setores_remover = self::whereNotIn('cod_set_replicado', $codigos_replicado)->get();

        foreach ($setores_remover as $setor) {
            $nome_antigo = $setor->nome;
            if (stripos($setor->nome, '(removido)') === false) {
                $setor->nome .= ' (removido)';
                $setor->save();
                $estatisticas['setores_removidos'][] = $nome_antigo;
            }
        }

        // Monta a mensagem de log
        $mensagem_log = "Sincronização de setores concluída\n";

        $mensagem_log .= "\nSetores criados (" . count($estatisticas['setores_criados']) . "):";
        foreach ($estatisticas['setores_criados'] as $nome) {
            $mensagem_log .= "\n - $nome";
        }

        $mensagem_log .= "\n\nSetores atualizados (" . count($estatisticas['setores_atualizados']) . "):";
        foreach ($estatisticas['setores_atualizados'] as $nome) {
            $mensagem_log .= "\n - $nome";
        }

        $mensagem_log .= "\n\nChefes adicionados (" . count($estatisticas['chefes_adicionados']) . "):";
        foreach ($estatisticas['chefes_adicionados'] as $chefe) {
            $mensagem_log .= "\n - $chefe";
        }

        $mensagem_log .= "\n\nSetores marcados como removidos (" . count($estatisticas['setores_removidos']) . "):";
        foreach ($estatisticas['setores_removidos'] as $nome) {
            $mensagem_log .= "\n - $nome";
        }

        // Loga e retorna mensagem
        Log::info($mensagem_log);
        return $mensagem_log;
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
