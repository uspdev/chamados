<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setor;
use App\Models\User;

class SetorEESCSeeder extends Seeder
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
                'sigla' => 'EESC',
                'nome' => 'Escola Engenharia São Carlos',
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
            $s = Setor::create($setor);
            # Vamos colocar gerente em alguns setores apenas
            if (rand(0,2)) $s->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Gerente']);
        }
    }
}
