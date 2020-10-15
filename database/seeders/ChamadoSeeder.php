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
            'assunto'        => 'Computador não liga',
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
            'fila_id'        =>  1
        ];

        $cht = Chamado::create($chamado);
        $cht->users()->attach(User::first()->id, ['funcao' => 'Autor']);
        Chamado::factory(10)->create()->each(function ($chamado) {
            $chamado->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Autor']);
        });
    }
}
