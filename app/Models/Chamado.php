<?php

namespace App\Models;

use App\Models\Comentario;
use App\Models\Fila;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class Chamado extends Model
{
    use HasFactory;

    #testar eager load para minimizar db hits
    #protected $with = ['users', 'fila', 'setor'];

    # para atribuição em massa
    protected $fillable = ['assunto', 'descricao', 'anotacoes'];

    # valor default
    protected $attributes = [
        'status' => 'Triagem',
    ];

    /**
     * Retorna os status possiveis no chamado.
     * Passou a ser gerado pela fila. Em uso somente pelas migrations.
     */
    public static function status()
    {
        return ['Triagem', 'Em Andamento', 'Fechado', 'Aguardando Solicitante', 'Aguardando Peças'];
    }
    
    /**
     * Retorna a cor para os labels.
     * 
     */
    public static function color( $chamado )
    {
        return $chamado->fila->getColortoLabel($chamado->status);
    }

    /**
     * Retorna array com anos selecionáveis, em ordem inversa
     */
    public static function anos()
    {
        return range(date('Y'), 2018, -1);
    }

    /**
     * Valores possiveis para pivot do relacionamento com users
     */
    #
    public static function pessoaPapeis($formSelect = false)
    {
        if ($formSelect) {
            return [
                'Observador' => 'Observador',
                'Atendente' => 'Atendente',
                'Autor' => 'Autor',
            ];
        } else {
            return ['Observador', 'Atendente', 'Autor'];
        }
    }

    /**
     * Se passado $ano, filtra por ele
     * https://laravel.com/docs/8.x/eloquent#local-scopes
     */
    public function scopeAno($query, $ano)
    {
        if ($ano) {
            return $query->whereYear('chamados.created_at', $ano);
        } else {
            return $query;
        }
    }

    /**
     * Se passado $nro, filtra por ele
     * https://laravel.com/docs/8.x/eloquent#local-scopes
     */
    public function scopeNro($query, $nro)
    {
        if ($nro) {
            return $query->where('nro', $nro)->latest();
        } else {
            return $query;
        }
    }

    /**
     * Se passado $assunto, faz busca tipo like e limita em 30 registros
     * https://laravel.com/docs/8.x/eloquent#local-scopes
     */
    public function scopeAssunto($query, $assunto)
    {
        if ($assunto) {
            $assunto = str_replace(' ', '%', $assunto);
            return $query->where('assunto', 'LIKE', '%' . $assunto . '%')->take(30)->latest();
        } else {
            return $query;
        }
    }

    /**
     * Lista os chamados autorizados para o usuário
     * considerando o ano e o perfil:
     * Se perfilAdmin mostra todos os chamados
     * Se perfilAtendente mostra todos os chamados das filas que atende
     * Se perfilUsuario mostra os chamados que ele está cadastrado como criador ou observador
     *
     * Vamos considerar chamados de filas desativadas
     */
    public static function listarChamados($ano, $nro = null, $assunto = null)
    {
        if (Gate::allows('perfilAdmin')) {
            $chamados = SELF::ano($ano)->nro($nro)->assunto($assunto)->get();
        } elseif (Gate::allows('perfilAtendente')) {
            $chamados = collect();
            $filas = \Auth::user()->filas;
            foreach ($filas as $fila) {
                $chamados = $chamados->merge($fila->chamados()->ano($ano)->nro($nro)->assunto($assunto)->get());
            }
        } elseif (Gate::allows('perfilUsuario')) {
            $chamados = \Auth::user()->chamados()->ano($ano)->nro($nro)->assunto($assunto)->get();
        } else {
            $chamados = collect();
        }
        $chamados = $chamados->unique('id');
        return $chamados;
    }

    /**
     * A numeração do chamado é sequencial por ano.
     * Para isso temos esse método que pega o próximo número disponível
     * Para evitar inconsistência a criação do chamado deve ser dentro de
     * uma transaction incluindo a obtenção do nro e o save no bd
     */
    public static function obterProximoNumero()
    {
        $nro = Chamado::whereYear('created_at', '=', date('Y'))->max('nro');
        return $nro + 1;
    }

    /**
     * o autorelacionamento n-n está usando um método para ida,
     * outro para a volta e um assessor para juntar os dois
     * tem solução melhor????
     */
    public function vinculadosIda()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamados_vinculados', 'chamado_id', 'vinculado_id')
            ->withPivot('acesso')
            ->withTimestamps();
    }

    public function vinculadosVolta()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamados_vinculados', 'vinculado_id', 'chamado_id')
            ->withPivot('acesso')
            ->withTimestamps();
    }

    # Assesor que junta
    public function getVinculadosAttribute()
    {
        return $this->vinculadosIda->merge($this->vinculadosVolta);
    }

    /**
     * relacionamento com users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_chamado')
            ->withPivot('papel')->withTimestamps();
    }

    /**
     * relacionamento com comentarios
     */
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    /**
     * relacionamento com fila
     */
    public function fila()
    {
        return $this->belongsTo(Fila::class);
    }

    /**
     * Relacionamento com arquivos
     */
    public function arquivos()
    {
        return $this->hasMany('App\Models\Arquivo');
    }

    /**
     * relacionamento com patrimonios
     */
    public function patrimonios()
    {
        return $this->belongsToMany('App\Models\Patrimonio', 'chamado_patrimonio')->withTimestamps();
    }
}
