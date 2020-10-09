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
                'template' => '{"dia":{"label":"Dia do atendimento","type":"date"}}'
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
            $fila->user()->attach(User::inRandomOrder()->first()->id, ['funcao'=>'Gerente']);
        }
        Fila::factory(10)->create()->each(function($fila) {
            $fila->user()->attach(User::inRandomOrder()->first()->id, ['funcao'=>'Gerente']);
        });
    }
}
