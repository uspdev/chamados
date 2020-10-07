<?php

namespace Database\Factories;

use App\Models\Fila;
use App\Models\Setor;
use Illuminate\Database\Eloquent\Factories\Factory;

class FilaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fila::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'descricao' => $this->faker->sentence,
            'setor_id' => Setor::inRandomOrder()->first()->id,
        ];
    }
}
