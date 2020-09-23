<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Chamado;

class Categoria extends Model
{
    use HasFactory;
    public function chamados()
    {
        return $this->hasMany(Chamados::class);
    }
}
