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
                'setor_id' => null
            ],
            [
                'sigla' => 'ATFN',
                'nome' => 'Assistência Financeira',
                'setor_id' => 1
            ],
            [
                'sigla' => 'ATAC',
                'nome' => 'Assistência Acadêmica',
                'setor_id' => 1
            ],
            [
                'sigla' => 'ATAD',
                'nome' => 'Assistência Administrativa',
                'setor_id' => 1
            ],
            [
                'sigla' => 'SVMANOB',
                'nome' => 'Serviço de manutenção e obras',
                'setor_id' => 4
            ],
        ];
        foreach ($setores as $setor) {
            Setor::create($setor);
        }
    }
}
