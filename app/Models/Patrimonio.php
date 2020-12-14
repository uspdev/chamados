<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uspdev\Replicado\Bempatrimoniado;
use Uspdev\Replicado\Pessoa;

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

    public function numFormatado()
    {
        return str_pad(substr($this->numpat, 0, -6), 3, '0', STR_PAD_LEFT) . '.' . substr($this->numpat, strlen($this->numpat) - 6);
    }

    public function responsavel($codpes)
    {
        return json_decode(json_encode(Pessoa::nomeCompleto($codpes)));
    }
}
