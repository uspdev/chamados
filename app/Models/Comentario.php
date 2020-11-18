<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Chamado;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'chamado_id', 'comentario', 'tipo'];

    public static function tipos() {
        return ['user', 'system'];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function chamado(){
        return $this->belongsTo(Chamado::class);
    }
}
