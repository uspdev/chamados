<?php

namespace Database\Seeders;

use App\Models\Chamado;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChamadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chamados = [
            [
                'nro' => 0,
                'assunto' => 'Monitor não liga 2019',
                'descricao' => 'Não dá sinal de vida.',
                'status' => 'Triagem',
                'extras' => '
                {
                    "predio" : "Administração",
                    "sala"   : "Sala 02",
                    "numpat" : "314.159266",
                    "dia"    : "1951-07-22"
                }',
                'fila_id' => 1,
                'anotacoes' => 'Deve estar com a fonte queimada 2019',
                'created_at' => '2019-10-26 15:50:44',
            ],
            [
                'nro' => 0,
                'assunto' => 'Computador não liga',
                'descricao' => 'Saiu fumaça da parte de trás.',
                'status' => 'Triagem',
                'extras' => '
                {
                    "predio" : "Administração",
                    "sala"   : "Sala 02",
                    "numpat" : "314.159265",
                    "dia"    : "1951-07-22"
                }',
                'fila_id' => 1,
                'anotacoes' => "Fonte queimada. Precisa trocar.",
            ],
            [
                'nro' => 0,
                'assunto' => 'Monitor não liga',
                'descricao' => 'Não dá sinal de vida.',
                'status' => 'Triagem',
                'extras' => '
                {
                    "predio" : "Administração",
                    "sala"   : "Sala 02",
                    "numpat" : "314.159266",
                    "dia"    : "1951-07-22"
                }',
                'fila_id' => 1,
                'anotacoes' => 'Deve estar com a fonte queimada',
            ],
        ];

        foreach ($chamados as $chamado) {
            $cht = \DB::transaction(function () use ($chamado) {
                $chamado['nro'] = Chamado::obterProximoNumero();
                $cht = Chamado::create($chamado);
                return $cht;
            });
            # preenchendo relacionamento com users
            $cht->users()->attach(User::first()->id, ['papel' => 'Autor']);
            $cht->users()->attach(User::inRandomOrder()->first()->id, ['papel' => 'Observador']);
        }

        # relacionando dois chamados
        $cht->vinculadosIda()->attach(1, ['acesso' => 'leitura']);

        for ($i = 0; $i < 10; $i++) {
            // o FOR aqui é para que o proximo numero do chamado seja
            // pego corretamente.
            Chamado::factory(1)->create()->each(function ($chamado) {
                $chamado->users()->attach(User::inRandomOrder()->first()->id, ['papel' => 'Autor']);

                # Se não estiver em triagem vamos adicionar atendente
                if ($chamado->status != 'Triagem') {
                    $atendente_ids = $chamado->fila->users->pluck('id')->all();
                    $chamado->users()->attach($atendente_ids[array_rand($atendente_ids)], ['papel' => 'Atendente']);
                }
                # vamos adicionar uma quantidade aleatória de observadores
                for ($i = 0; $i < rand(0, 2); $i++) {
                    $chamado->users()->attach(User::inRandomOrder()->first()->id, ['papel' => 'Observador']);
                }
            });
        }
    }
}
