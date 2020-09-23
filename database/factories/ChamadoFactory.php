<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Chamado;
use App\Models\User;
use App\Models\Categoria;

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
            return [
                'user_id'        =>   User::factory()->create()->id,
                'complexidade'   =>  $complexidades[array_rand($complexidades)],  
                'categoria_id'   =>  Categoria::factory()->create()->id,
                'predio'         =>  $predios[array_rand($predios)],
                'sala'           =>  $this->faker->randomDigit,
                'patrimonio'     =>  $this->faker->unique()->numberBetween(10000, 999999),
                'chamado'        =>  $this->faker->sentence,
            ];
    }
}
