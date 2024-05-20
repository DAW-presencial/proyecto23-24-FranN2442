<?php

namespace Database\Seeders\Employees;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [

            [
                'full_name' => 'Manuel Gonzalez Silva',
                'email' => 'manuel@gmail.com',
                'pin' => '2442',
                'tel_num' => '657911913',
                'role' => 'admin',
                'restaurant_id' => 1,
            ],
            [
                'full_name' => 'Maria Cano Fernandez',
                'email' => 'maria@gmail.com',
                'pin' => '2222',
                'tel_num' => '768855645',
                'role' => 'employee',
                'restaurant_id' => 1,
            ],
            [
                'full_name' => 'Teresa Gimenez Perez',
                'email' => 'teresa@gmail.com',
                'pin' => '1111',
                'tel_num' => '8796756444',
                'role' => 'admin',
                'restaurant_id' => 2,
            ],
            [
                'full_name' => 'Miquel Mosson Parra',
                'email' => 'miquel@gmail.com',
                'pin' => '6666',
                'tel_num' => '878657453',
                'role' => 'employee',
                'restaurant_id' => 2,
            ],
            [
                'full_name' => 'Josefina Viejo Silva',
                'email' => 'fina@gmail.com',
                'pin' => '6754',
                'tel_num' => '746373737',
                'role' => 'admin',
                'restaurant_id' => 3,
            ],
            [
                'full_name' => 'Joaquin Gonzalez Silva',
                'email' => 'joaquin@gmail.com',
                'pin' => '2121',
                'tel_num' => '987788526',
                'role' => 'admin',
                'restaurant_id' => 3,
            ],
            [
                'full_name' => 'Jarry Stefano Suarez',
                'email' => 'jarry@gmail.com',
                'pin' => '9999',
                'tel_num' => '234435324',
                'role' => 'admin',
                'restaurant_id' => 4,
            ],
            [
                'full_name' => 'Pedro Torres del Toro',
                'email' => 'pedro@gmail.com',
                'pin' => '4256',
                'tel_num' => '871822893',
                'role' => 'employee',
                'restaurant_id' => 4,
            ],
            [
                'full_name' => 'Sara Leon Silva',
                'email' => 'sara@gmail.com',
                'pin' => '2223',
                'tel_num' => '678829182',
                'role' => 'admin',
                'restaurant_id' => 5,
            ],
            [
                'full_name' => 'Laura Viejo Silva',
                'email' => 'laura@gmail.com',
                'pin' => '3311',
                'tel_num' => '901920233',
                'role' => 'employee',
                'restaurant_id' => 5,
            ],
            [
                'full_name' => 'Manuela Viejo Silva',
                'email' => 'manuela@gmail.com',
                'pin' => '2212',
                'tel_num' => '765657789',
                'role' => 'admin',
                'restaurant_id' => 6,
            ],
            [
                'full_name' => 'Lidia Viejo Silva',
                'email' => 'lidia@gmail.com',
                'pin' => '3311',
                'tel_num' => '900827272',
                'role' => 'employee',
                'restaurant_id' => 6,
            ],
            [
                'full_name' => 'Pepe Melo Silva',
                'email' => 'pepe@gmail.com',
                'pin' => '3311',
                'tel_num' => '726473829',
                'role' => 'admin',
                'restaurant_id' => 7,
            ],
            [
                'full_name' => 'Jose Viejo Silva',
                'email' => 'jose@gmail.com',
                'pin' => '3311',
                'tel_num' => '142324111',
                'role' => 'employee',
                'restaurant_id' => 7,
            ],
            [
                'full_name' => 'Eugenio Navarro Silva',
                'email' => 'eugenio@gmail.com',
                'pin' => '3311',
                'tel_num' => '763252321',
                'role' => 'admin',
                'restaurant_id' => 8,
            ],
            [
                'full_name' => 'Gabriel Viejo Silva',
                'email' => 'gabriel@gmail.com',
                'pin' => '3311',
                'tel_num' => '213212321',
                'role' => 'employee',
                'restaurant_id' => 8,
            ],
            [
                'full_name' => 'Ivan Viejo Silva',
                'email' => 'ivan@gmail.com',
                'pin' => '3311',
                'tel_num' => '43213212º',
                'role' => 'admin',
                'restaurant_id' => 9,
            ],
            [
                'full_name' => 'Ana Viejo Silva',
                'email' => 'ana@gmail.com',
                'pin' => '3311',
                'tel_num' => '432132135',
                'role' => 'employee',
                'restaurant_id' => 9,
            ],
            [
                'full_name' => 'Enrrique Viejo Silva',
                'email' => 'henrry@gmail.com',
                'pin' => '3311',
                'tel_num' => '534231321',
                'role' => 'admin',
                'restaurant_id' => 10,
            ],
            [
                'full_name' => 'Abel Viejo Silva',
                'email' => 'abel@gmail.com',
                'pin' => '3311',
                'tel_num' => '234123214',
                'role' => 'employee',
                'restaurant_id' => 10,
            ],
            [
                'full_name' => 'Pato Viejo Silva',
                'email' => 'pato@gmail.com',
                'pin' => '3311',
                'tel_num' => '634132421',
                'role' => 'admin',
                'restaurant_id' => 11,
            ],
            [
                'full_name' => 'Victor Viejo Silva',
                'email' => 'victor@gmail.com',
                'pin' => '3311',
                'tel_num' => '532132134',
                'role' => 'employee',
                'restaurant_id' => 11,
            ],
            [
                'full_name' => 'Victoria Viejo Silva',
                'email' => 'victoria@gmail.com',
                'pin' => '3311',
                'tel_num' => '543243153',
                'role' => 'admin',
                'restaurant_id' => 12,
            ],
            [
                'full_name' => 'Yumara Viejo Silva',
                'email' => 'yumi@gmail.com',
                'pin' => '3311',
                'tel_num' => '342135673',
                'role' => 'employee',
                'restaurant_id' => 12,
            ],


        ];

        foreach ($employees as $employee) {
            Employee::factory()->create($employee);
        }
    }
}
