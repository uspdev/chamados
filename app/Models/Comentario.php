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

    public static function tipos()
    {
        return ['user', 'system'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }
}
