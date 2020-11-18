<?php

namespace Database\Factories;

use App\Models\Chamado;
use App\Models\Comentario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComentarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comentario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tipos = Comentario::tipos();
        return [
            'comentario' => $this->faker->sentence,
            'tipo' => $tipos[array_rand($tipos)],
            'user_id' => User::inRandomOrder()->first()->id,
            'chamado_id' => Chamado::inRandomOrder()->first()->id,
        ];
    }
}
