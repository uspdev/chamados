<?php

use Illuminate\Database\Seeder;
use App\Categoria;

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

        factory(Categoria::class, 10)->create();
    }
}