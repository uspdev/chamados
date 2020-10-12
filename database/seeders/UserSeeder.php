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
        $user = [
            'name'     => 'Marisa',
            'codpes'   =>  rand(),
            'email'    => 'marisa@gmail.com',
        ];
        User::create($user);
        User::factory(10)->create();
    }
}
