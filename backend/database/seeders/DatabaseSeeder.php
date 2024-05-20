<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Design;
use App\Models\Employee;
use App\Models\Restaurant;
use App\Models\User;
use Database\Seeders\Designs\DesignSeeder;
use Database\Seeders\Employees\EmployeeSeeder;
use Database\Seeders\Restaurants\RestaurantSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([

            UserSeeder::class,
            RestaurantSeeder::class,
            EmployeeSeeder::class,
            DesignSeeder::class
        ]);

    }
}
