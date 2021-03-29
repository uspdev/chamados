<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    /**
     * relacionamento com chamado
     */
    public function chamado()
    {
        return $this->belongsTo(Chamado::class);
    }

    /**
     * relacionamento com user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
