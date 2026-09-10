<?php

namespace App\Models;

use App\Models\Chamado;
use App\Models\User;
use App\Observers\ComentarioObserver;
use Carbon\Carbon;
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

    public function podeSerEditadoPor(User $user)
    {
        if ($this->tipo != 'user') {
            return false;
        }

        if ($this->user_id != $user->id) {
            return false;
        }

        if (!$this->chamado->fila->config->editar_comentarios) {
            return false;
        }

        $timeout = $this->chamado->fila->config->editar_comentarios_timeout_horas;
        if ($timeout == 0) {
            return true;
        }

        return $this->created_at->greaterThanOrEqualTo(Carbon::now()->subHours($timeout));
    }

    public function foiEditado()
    {
        return $this->created_at && $this->updated_at && $this->updated_at->greaterThan($this->created_at);
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
