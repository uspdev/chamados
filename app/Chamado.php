<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function comentarios()
    {
        return $this->hasMany('App\Comentario');
    }

    public function categoria(){
        return $this->belongsTo('App\Categoria');
    }
}
