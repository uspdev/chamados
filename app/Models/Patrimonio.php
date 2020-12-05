<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patrimonio extends Model
{
    use HasFactory;

    /**
     * Relacionamento com chamados
     */
    public function chamados()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamado_patrimonio')->withTimestamps();
    }
}
