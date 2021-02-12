<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chamado;
use App\Models\Comentario;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // desativando eventos no seeder
        Chamado::flushEventListeners();
        Comentario::flushEventListeners();

        // so vamos fazer o seeder de setores se houver replicado
        $setor_seeder = (config('chamados.usar_replicado')) ? SetorReplicadoSeeder::class : SetorSeeder::class;

        $this->call([
            UserSeeder::class,
            $setor_seeder,
            FilaSeeder::class,
            ChamadoSeeder::class,
            ComentarioSeeder::class,
        ]);
    }
}
