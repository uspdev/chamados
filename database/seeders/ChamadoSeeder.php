<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Chamado;

class ChamadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chamado = [
            'chamado'        => 'Computador não liga',
            'predio'         => 'Administração',
            'sala'           => 'Sala 02',
            'patrimonio'     => '008.047977',
            'status'         => 'Triagem',
            'complexidade'   => 'alta',
            'atribuido_para' =>  5385361,
            'user_id'        =>  1,
            'fila_id'        =>  1
        ];

        Chamado::create($chamado);
        Chamado::factory(10)->create();
     
    }
}
