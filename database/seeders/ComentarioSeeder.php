<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comentario;

class ComentarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comentarios = [
            [
                'comentario' => 'Primeiro comentário',
                'user_id' => 1,
                'chamado_id' => 1,
            ],
            [
                'comentario' => 'Segundo comentário',
                'user_id' => 1,
                'chamado_id' => 1,
            ],
        ];
        foreach ($comentarios as $comentario) {
            Comentario::create($comentario);
        }
        Comentario::factory(50)->create();

    }
}
