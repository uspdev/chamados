<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uspdev\Replicado\Bempatrimoniado;
use Uspdev\Replicado\Pessoa;

class Patrimonio extends Model
{
    use HasFactory;

    protected $replicado = '';

    /**
     * Obtém dados do replicado, retorna e guarda no objeto
     */
    public function replicado()
    {
        if (empty($this->replicado)) {
            $this->replicado = (object) Bempatrimoniado::dump($this->numpat);
        }
        return $this->replicado;
    }

    /**
     * Retorna o número de patrimônio no formato 000.000000
     */
    public function numFormatado()
    {
        return str_pad(substr($this->numpat, 0, -6), 3, '0', STR_PAD_LEFT) . '.' . substr($this->numpat, strlen($this->numpat) - 6);
    }

    /**
     * Obtém o nome completo (nompesttd) do responsável pelo patrimonio
     */
    public function responsavel($codpes)
    {
        return Pessoa::nomeCompleto($codpes);
    }

    /**
     * Relacionamento com chamados
     */
    public function chamados()
    {
        return $this->belongsToMany('App\Models\Chamado', 'chamado_patrimonio')->withTimestamps();
    }
}
