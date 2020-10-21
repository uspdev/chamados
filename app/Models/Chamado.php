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
     * relacionamento com users
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_chamado')
            ->withPivot('funcao')->withTimestamps();
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

    protected function vinculadosVolta()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamados_vinculados', 'vinculado_id', 'chamado_id')
            ->withPivot('acesso')
            ->withTimestamps();
    }

    public function getVinculadosAttribute()
    {
        return $this->vinculadosIda->merge($this->vinculadosVolta);
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

    public static function obterProximoNumero() {
        $nro = Chamado::whereYear('created_at', '=', date('Y'))->max('nro');
        $ano = date('Y');
        return $nro+1;
    }

    public function arquivos() {
        return $this->hasMany('App\Models\Arquivo');
    }
}
