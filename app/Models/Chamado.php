<?php

namespace App\Models;

use App\Models\Comentario;
use App\Models\Fila;
use App\Observers\ChamadoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
     * The attributes that should be mutated to dates.
     * https://laravel.com/docs/5.6/eloquent-mutators#date-mutators
     *
     * @var array
     */
    protected $dates = [
        'fechado_em',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        Chamado::observe(ChamadoObserver::class);
    }

    /**
     * Retorna os status possiveis no chamado.
     * Passou a ser gerado pela fila. Em uso somente pelas migrations.
     */
    public static function status()
    {
        return ['Triagem', 'Em Andamento', 'Fechado', 'Aguardando Solicitante', 'Aguardando Peças'];
    }

    /**
     * config-status: Retorna a cor correspondente para o label do estado do chamado
     * 
     * Se não encontrado retorna 'secondary'
     * Estava anterirmente em fila, mas faz mais sentido aqui
     * 
     * @return String
     */
    public function retornarCor()
    {
        $status = $this->fila->config->status;
        if ($status) {
            foreach ($status as $item) {
                if (strtolower($item->label) == $this->status) {
                    return $item->color;
                }
            }
        }
        return 'secondary';
    }

    /**
     * Retorna array com anos selecionáveis, em ordem inversa
     * TODO: precisa ajustar para pegar os anos do BD
     */
    public static function anos()
    {
        return range(date('Y'), 2020, -1);
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
     * Esconde/mostra os chamados finalizados, ou seja, aqueles "Encerrados" há mais de 10 dias
     *
     * https://laravel.com/docs/8.x/eloquent#local-scopes
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param Bool $finalizado true: mostra finalizados, false: esconde
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFinalizado($query, Bool $finalizado)
    {
        if (!$finalizado) {
            return $query->where('status', '!=', 'Fechado')
                ->orWhere('fechado_em', '>', now()->subDays(10));
        } else {
            return $query;
        }
    }

    /**
     * Lista os chamados autorizados para o usuário
     *
     * considerando o ano e o perfil:
     * Se perfiladmin mostra todos os chamados
     * Se perfilatendente mostra todos os chamados das filas que atende
     * Se perfilusuario mostra os chamados que ele está cadastrado como criador ou observador
     * Os filtros por nro e assunto são usados em consultas ajax
     *
     * Vamos considerar chamados de filas desativadas
     */
    public static function listarChamados($ano, $nro = null, $assunto = null, $finalizado = false)
    {
        if (Gate::allows('perfiladmin')) {
            $chamados = SELF::ano($ano)->nro($nro)->assunto($assunto)->finalizado($finalizado)->get();
        } elseif (Gate::allows('perfilatendente')) {
            $chamados = collect();
            foreach (Auth::user()->filas as $fila) {
                $chamados = $chamados->merge($fila->chamados()->ano($ano)->nro($nro)->assunto($assunto)->finalizado($finalizado)->get());
            }
        } elseif (Gate::allows('perfilusuario')) {
            $chamados = Auth::user()->chamados()
                ->wherePivotIn('papel', ['Autor', 'Observador'])
                ->ano($ano)->nro($nro)->assunto($assunto)->finalizado($finalizado)->get();
        } else {
            $chamados = collect();
        }

        // eliminando repetidos
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
     * Retorna se o chamado está fechado há algum tempo
     * Nesse caso não poderá mais ser reaberto
     */
    public function isFinalizado()
    {
        # depois de 10 dias não pode mais ser reaberto
        if ($this->status == 'Fechado' && $this->fechado_em->addDays(10) < now()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Mostra o prazo para poder reabrir o chamado
     */
    public function reabrirEm()
    {
        return $this->fechado_em->addDays(10);
    }

    /**
     * Mostra as pessoas que tem vonculo com o chamado.
     * 
     * Se informado $pivot, retorna somente o 1o. User, se não, retorna a lista completa 
     * 
     * @param $pivot Papel da pessoa no chamado (autor, observador, atendente, null = todos) 
     * @return App\Models\User|Collection
     */
    public function pessoas($pivot = null)
    {
        if ($pivot) {
            return $this->users()->wherePivot('papel', $pivot)->first();
        } else {
            return $this->users()->withPivot('papel');
        }
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

    # não guardar tags html
    public function setDescricaoAttribute($value)
    {
        $this->attributes['descricao'] = strip_tags($value);
    }

    # porém mostrar quebras de linhas
    public function getDescricaoAttribute($value)
    {
        return nl2br($value);
    }
}
