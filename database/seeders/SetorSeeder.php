<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setor;

class SetorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setores = [
            [
                'sigla' => 'UND',
                'nome' => 'Nome da unidade',
                'setores_id' => null
            ],
            [
                'sigla' => 'ATFN',
                'nome' => 'Assistência Financeira',
                'setores_id' => 1
            ],
            [
                'sigla' => 'ATAC',
                'nome' => 'Assistência Acadêmica',
                'setores_id' => 1
            ],
            [
                'sigla' => 'ATAD',
                'nome' => 'Assistência Administrativa',
                'setores_id' => 1
            ],
            [
                'sigla' => 'SVMANOB',
                'nome' => 'Swerviço de manutenção e obras',
                'setores_id' => 4
            ],
        ];
        foreach ($setores as $setor) {
            Setor::create($setor);
        }

    }
}
