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
            'descricao'      => 'Saiu fumaça da parte de trás.',
            'status'         => 'Triagem',
            'complexidade'   =>  null,
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
        $cht->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Atribuidor']);
        $cht->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Atendente']);
        Chamado::factory(10)->create()->each(function ($chamado) {
            $chamado->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Autor']);
        });
        $cht = Chamado::inRandomOrder()->first();
        $cht->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Atribuidor']);
        $cht->users()->attach(User::inRandomOrder()->first()->id, ['funcao' => 'Atendente']);
    }
}
