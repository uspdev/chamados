<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
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
