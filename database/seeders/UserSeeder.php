<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requisitante = [
            'name'     => 'Marisa',
            'codpes'   =>  9848815,
            'email'    => 'marisa@gmail.com',
              
        ];
        User::create($requisitante);
        User::factory(10)->create();
    }
}