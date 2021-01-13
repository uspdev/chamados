<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Chamado;
use App\Models\Fila;
use App\Models\User;

class ChamadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chamado::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = Chamado::status();
        $status = $statuses[array_rand($statuses)];
        $fechado_em = null;
        $nro = Chamado::obterProximoNumero();

        if ($status == 'Fechado') {
            $fechado_em = $this->faker->dateTime($max = 'now', $timezone = null);
        }
            return [
                'nro'            =>  $nro,
                'assunto'        =>  $this->faker->sentence,
                'descricao'      =>  $this->faker->sentence,
                'status'         =>  $status,
                'fila_id'        =>  Fila::inRandomOrder()->first()->id,
                'fechado_em'     =>  $fechado_em
            ];
    }
}
