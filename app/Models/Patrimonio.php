<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uspdev\Replicado\Bempatrimoniado;

class Patrimonio extends Model
{
    use HasFactory;

    public function replicado()
    {
        return json_decode(json_encode(Bempatrimoniado::dump($this->numpat)));
    }

    /**
     * Relacionamento com chamados
     */
    public function chamados()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamado_patrimonio')->withTimestamps();
    }
}
