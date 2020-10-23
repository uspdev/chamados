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
                'nome' => 'Informática',
                'descricao' => 'Atendimento geral',
                'estado' => 'Em produção',
                'setor_id' => 5,
                'template' => '{
                    "predio": {
                        "label": "Prédio",
                        "type" : "text"
                    },
                    "sala": {
                        "label" : "Sala",
                        "type"  : "text",
                        "help"  : "Exemplo: sala 02",
                        "value" : "Sala 2"
                    },
                    "numpat": {
                        "label": "Patrimônios",
                        "type" : "text",
                        "help" : "Exemplo: <strong>008.047977</strong><br>Use vírgula caso o procedimento de atendimento seja idêntico para múltiplos computadores. Exemplo: <strong>008.047977,008.048593</strong>"
                    },
                    "dia": {
                        "label": "Dia do atendimento",
                        "type" : "date",
                        "validate" : "required"
                    },
                    "tipo": {
                        "label"   : "Tipo de problema",
                        "type"    : "select",
                        "value" : {
                            "telefonia" : "Problemas com telefone",
                            "impressora": "Problemas com impressora",
                            "software"  : "Instalação de software",
                            "virus"     : "Computador com vírus",
                            "site"      : "Não consigo atualizar o site do meu setor",
                            "outro"     : "Não sei classificar meu problema"
                        }
                    }
                }',
            ],
            [
                'nome' => 'Zeladoria',
                'descricao' => 'Atendimento geral',
                'estado' => 'Em produção',
                'setor_id' => 6,
            ],
            [
                'nome' => 'Informática',
                'descricao' => 'Atendimento geral',
                'estado' => 'Em produção',
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
