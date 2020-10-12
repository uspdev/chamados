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
        $complexidades = Chamado::complexidades();
        $predios = Chamado::predios();
        $statuses = Chamado::status();
        $status = $statuses[array_rand($statuses)];
        $atribuido_em = $atribuido_para = $triagem_por = $fechado_em = null;
        if ($status == 'Atribuído') {
            $atribuido_em = $this->faker->dateTime($max = 'now', $timezone = null);
            $atribuido_para = User::inRandomOrder()->first()->codpes;
            $triagem_por = User::inRandomOrder()->first()->codpes;
        } 

        if ($status == 'Fechado') {
            $fechado_em = $this->faker->dateTime($max = 'now', $timezone = null);
        }
            return [
                'chamado'        =>  $this->faker->sentence,
                'predio'         =>  $predios[array_rand($predios)],
                'sala'           =>  $this->faker->randomDigit,
                'patrimonio'     =>  $this->faker->unique()->numberBetween(10000, 999999),
                'status'         =>  $status,
                'complexidade'   =>  $complexidades[array_rand($complexidades)],
                'user_id'        =>  User::inRandomOrder()->first()->id,
                'fila_id'        =>  Fila::inRandomOrder()->first()->id,
                'fechado_em'     =>  $fechado_em,
                'atribuido_em'     =>  $atribuido_em,
                'atribuido_para'     =>  $atribuido_para,
                'triagem_por'     =>  $triagem_por,
            ];
    }
}
