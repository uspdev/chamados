<?php

namespace App\Models;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\ComentarioObserver;

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
        Comentario::observe(ComentarioObserver::class);
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
