<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
                'setor_id' => 5
            ],
            [
                'nome' => 'Zeladoria',
                'descricao' => 'Atendimento geral',
                'setor_id' => 6
            ],
        ];
        foreach ($filas as $fila) {
            \App\Models\Fila::create($fila);
        }
    }
}
