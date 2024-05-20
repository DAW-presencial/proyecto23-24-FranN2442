<?php

namespace Database\Seeders\Users;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // * 5 usuarios random
        User::factory(5)->create();

        // * Nuestros usuarios
        User::factory()->create([

            'full_name' => "Francisco Javier Gonzalez Viejo",
            'email' => "fran@gmail.com",
            'password' => Hash::make('password'),

        ]);

        User::factory()->create([

            'full_name' => "Samuel Piedra Mite",
            'email' => "samu@gmail.com",
            'password' => Hash::make('password'),

        ]);
    }
}
