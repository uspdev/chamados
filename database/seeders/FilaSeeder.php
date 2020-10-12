<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Fila;
use \App\Models\User;

class FilaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filas = [
            [
                'nome' => 'Informatica',
                'descricao' => 'Atendimento geral',
                'setor_id' => 5,
                'template' => '{
                    "predio": {
                        "label":"Prédio",
                        "type":"text"
                    },
                    "sala": {
                        "label":"Sala",
                        "type":"text"
                    },
                    "numpat": {
                        "label":"Patrimônios",
                        "type":"text"
                    },
                    "dia": {
                        "label":"Dia do atendimento",
                        "type":"date"
                    }
                }',
            ],
            [
                'nome' => 'Zeladoria',
                'descricao' => 'Atendimento geral',
                'setor_id' => 6,
            ],
            [
                'nome' => 'Informática',
                'descricao' => 'Atendimento geral',
                'setor_id' => 7,
            ],
        ];
        foreach ($filas as $fila) {
            $fila = Fila::create($fila);
            $fila->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Gerente']);
            for ($i = 0; $i < rand(3, 5); $i++) {
                $fila->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Atendente']);
            }
        }
        Fila::factory(10)->create()->each(function ($fila) {
            $fila->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Gerente']);
        });
    }
}
