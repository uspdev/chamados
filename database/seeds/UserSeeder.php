<?php

use Illuminate\Database\Seeder;
use App\User;

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
        factory(App\User::class, 10)->create();
    }
}