<?php

namespace App\Models;

use App\Models\Comentario;
use App\Models\Fila;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;

    #testar eager load para minimizar db hits
    #protected $with = ['users', 'fila', 'setor'];

    /**
     * Constantes usadas no bd
     */
    public static function complexidades()
    {
        return ['baixa', 'média', 'alta'];
    }

    public static function status()
    {
        return ['Triagem', 'Atribuído', 'Fechado'];
    }

    # valores possiveis para pivot do relacionamento com users
    public static function pessoaFuncoes()
    {
        return ['Atendente', 'Autor', 'Observador'];
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
}
