<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Chamado;
use Faker\Generator as Faker;
use App\User;
use App\Categoria;


$factory->define(Chamado::class, function (Faker $faker) {

    $complexidades = Chamado::complexidades();
    $predios = Chamado::predios();
        return [
            'user_id'  =>  factory(User::class)->create()->id,
            'complexidade'   =>  $complexidades[array_rand($complexidades)],  
            'categoria_id'   =>  factory(Categoria::class)->create()->id,
            'predio'         =>  $predios[array_rand($predios)],
            'sala'           =>  $faker->randomDigit,
            'patrimonio'     =>  $faker->unique()->numberBetween(10000, 999999),
            'chamado'        =>  $faker->sentence,
        ];
});