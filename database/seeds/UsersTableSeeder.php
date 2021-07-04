<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        User::create([
            'name'      => 'Eslam Gamal',
            'email'     => 'eslam@gmail.com',
            'password'  => bcrypt('123123123')
        ]);

        User::create([
            'name'      => 'Ali Gamal',
            'email'     => 'ali@gmail.com',
            'password'  => bcrypt('123123123')
        ]);

        User::create([
            'name'      => 'Sami Mansour',
            'email'     => 'sami@gmail.com',
            'password'  => bcrypt('123123123')
        ]);
    }
}
