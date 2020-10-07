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
                'sigla' => 'STI',
                'nome' => 'Seção técnica de informática',
                'setor_id' => 1
            ],
            [
                'sigla' => 'SVBIBL',
                'nome' => 'Serviço de biblioteca',
                'setor_id' => 1
            ],
            [
                'sigla' => 'DEPTO1',
                'nome' => 'Departamento acadêmico 1',
                'setor_id' => 1
            ],
            [
                'sigla' => 'SVMANOB',
                'nome' => 'Serviço de manutenção e obras',
                'setor_id' => 4
            ],
            [
                'sigla' => 'SVCompras',
                'nome' => 'Serviço de compras',
                'setor_id' => 2
            ],
            [
                'sigla' => 'SVGRAD',
                'nome' => 'Serviço de graduação',
                'setor_id' => 3
            ],

        ];
        foreach ($setores as $setor) {
            Setor::create($setor);
        }
    }
}
