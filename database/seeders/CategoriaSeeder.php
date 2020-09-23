<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoria1 = [
        'nome' => 'Categoria 1'
        ];
        $categoria2 = [
        'nome' => 'Categoria 2' 
        ];
        Categoria::create($categoria1);
        Categoria::create($categoria2);

        Categoria::factory(10)->create();
    }
}