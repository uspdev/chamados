<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Chamado;
use App\Models\User;

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
            'status'         => 'Triagem',
            'complexidade'   =>  null,
            'atribuido_para' =>  User::inRandomOrder()->first()->id,
            'extras'         => '{
                "predio" : "Administração",
                "sala"   : "Sala 02",
                "numpat" : "314.159265",
                "dia"    : "1951-07-22",
                "obs"    : "Fonte queimada. Precisa trocar."
            }',
            'user_id'        =>  1,
            'fila_id'        =>  1
        ];

        Chamado::create($chamado);
        Chamado::factory(10)->create();
     
    }
}
