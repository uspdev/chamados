<?php

namespace App\Models;

use App\Models\Chamado;
use App\Models\User;
use App\Observers\ComentarioObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'chamado_id', 'comentario', 'tipo'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        # eventos desta classe são monitorados
        Comentario::observe(ComentarioObserver::class);
    }

    /**
     * Cria novos comentários do tipo 'system'
     *
     * @param \App\Models\Chamado $chamado
     * @param String $comentario
     * @return \App\Models\Comentario objeto do novo comentário criado
     */
    public static function criarSystem($chamado, $comentario)
    {
        $c = new Comentario();
        $c->user_id = \Auth::user()->id;
        $c->chamado_id = $chamado->id;
        $c->tipo = 'system';
        $c->comentario = $comentario;
        $c->save();
        return $c;
    }

    /**
     * os tipos de comentários. Aparecerão em cards separados no chamado.
     */
    public static function tipos()
    {
        return ['user', 'system'];
    }

    /**
     * relacionamento com user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relacionamento com chamado
     */
    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}
