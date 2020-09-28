<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setor extends Model
{
    use HasFactory;
    protected $table='setores';
    
    protected $fillable = [
        'sigla',
        'nome',
        'setores_id',
    ];

    const rules = array(
        'sigla' => ['required','max:15'],
        'nome' => ['required', 'max:255'],
        'setores_id' => 'required|numeric',
    );

    const fields = [
        [
            'name' => 'sigla',
            'label' => 'Sigla',
        ],
        [
            'name' => 'nome',
            'label' => 'Nome',
        ],
        [
            'name' => 'setores_id',
            'label' => 'Pai',
            'type' => 'number',
        ],
    ];
}
