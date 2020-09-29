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
                'setores_id' => null
            ],
        ];
        foreach ($filas as $fila) {
            \App\Models\Fila::create($fila);
        }
    }
}
