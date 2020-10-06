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
        $status = Chamado::status();
            return [
                'chamado'        =>  $this->faker->sentence,
                'predio'         =>  $predios[array_rand($predios)],
                'sala'           =>  $this->faker->randomDigit,
                'patrimonio'     =>  $this->faker->unique()->numberBetween(10000, 999999),
                'status'         =>  $status[array_rand($status)],
                'complexidade'   =>  $complexidades[array_rand($complexidades)],
                'user_id'        =>  User::factory()->create()->id,
                'fila_id'        =>  Fila::factory()->create()->id
            ];
    }
}
