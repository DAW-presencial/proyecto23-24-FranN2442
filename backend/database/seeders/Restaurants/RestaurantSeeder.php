<?php

namespace Database\Seeders\Restaurants;

use App\Models\Restaurant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $restaurants = [
            [

                'name' => 'Restaurante Rincon del Patio',
                'address' => 'Avenida Cala Agulla, 116',
                'postal_code' => '07590',
                'location' => 'Calaratjada',
                'category' => 'Comida Española',
                'tel_num' => '871798904',
                'email' => 'rincondelpatio@gmail.com',
                'password' => Hash::make('rincondelpatio1234'),
                'tourns' => 
                ['turno0' => ['start' => '12:00','end' => '15:00','tourn_name' => 'Comida'],
                'turno1' => ['start' => '18:00','end' => '22:00','tourn_name' => 'Cena']
                ]
            ],
            [

                'name' => 'Restaurante los Arcos',
                'address' => 'Carrer Gremi, 22',
                'postal_code' => '07007',
                'location' => 'Palma',
                'category' => 'Tapas',
                'tel_num' => '871722904',
                'email' => 'losarcos@gmail.com',
                'password' => Hash::make('losarcos1234'),
                'tourns' => [
                    'turno0' => ['start' => '09:00','end' => '12:00','tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '13:00','end' => '16:00','tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:00','end' => '23:00','tourn_name' => 'Cena'],
                ]
            ],
            [
                'name' => 'Restaurante El Mirador',
                'address' => 'Av. de la Mar, 15',
                'postal_code' => '08005',
                'location' => 'Barcelona',
                'category' => 'Mediterránea',
                'tel_num' => '932157890',
                'email' => 'elmirador@gmail.com',
                'password' => Hash::make('elmirador1234'),
                'tourns' => [
                    'turno0' => ['start' => '08:00', 'end' => '11:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:00', 'end' => '15:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '18:00', 'end' => '22:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Bistro del Mar',
                'address' => 'Calle del Sol, 9',
                'postal_code' => '29015',
                'location' => 'Málaga',
                'category' => 'Mariscos',
                'tel_num' => '951234567',
                'email' => 'bistrodelmar@gmail.com',
                'password' => Hash::make('bistrodelmar1234'),
                'tourns' => [
                    'turno0' => ['start' => '07:30', 'end' => '10:30', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:30', 'end' => '15:30', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:00', 'end' => '23:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'La Parrilla',
                'address' => 'Calle Mayor, 42',
                'postal_code' => '41001',
                'location' => 'Sevilla',
                'category' => 'Barbacoa',
                'tel_num' => '954123890',
                'email' => 'laparrilla@gmail.com',
                'password' => Hash::make('laparrilla1234'),
                'tourns' => [
                    'turno0' => ['start' => '08:00', 'end' => '11:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '13:00', 'end' => '16:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:30', 'end' => '23:30', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Veggie Delight',
                'address' => 'Paseo de Gracia, 50',
                'postal_code' => '08007',
                'location' => 'Barcelona',
                'category' => 'Vegetariana',
                'tel_num' => '933456789',
                'email' => 'veggiedelight@gmail.com',
                'password' => Hash::make('veggiedelight1234'),
                'tourns' => [
                    'turno0' => ['start' => '09:00', 'end' => '12:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '13:00', 'end' => '16:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '18:00', 'end' => '22:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Sushi Express',
                'address' => 'Calle del Príncipe, 22',
                'postal_code' => '28012',
                'location' => 'Madrid',
                'category' => 'Japonesa',
                'tel_num' => '915678123',
                'email' => 'sushiexpress@gmail.com',
                'password' => Hash::make('sushiexpress1234'),
                'tourns' => [
                    'turno0' => ['start' => '09:00', 'end' => '12:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:30', 'end' => '15:30', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '18:00', 'end' => '22:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Pasta e Basta',
                'address' => 'Via Roma, 18',
                'postal_code' => '46001',
                'location' => 'Valencia',
                'category' => 'Italiana',
                'tel_num' => '963852741',
                'email' => 'pastaebasta@gmail.com',
                'password' => Hash::make('pastaebasta1234'),
                'tourns' => [
                    'turno0' => ['start' => '08:00', 'end' => '11:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:00', 'end' => '15:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:00', 'end' => '23:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Café Central',
                'address' => 'Gran Vía, 10',
                'postal_code' => '28013',
                'location' => 'Madrid',
                'category' => 'Café',
                'tel_num' => '917234567',
                'email' => 'cafecentral@gmail.com',
                'password' => Hash::make('cafecentral1234'),
                'tourns' => [
                    'turno0' => ['start' => '08:00', 'end' => '11:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:30', 'end' => '15:30', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '17:00', 'end' => '21:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Grill & Chill',
                'address' => 'Rambla de Catalunya, 25',
                'postal_code' => '08007',
                'location' => 'Barcelona',
                'category' => 'Parrilla',
                'tel_num' => '932234567',
                'email' => 'grillandchill@gmail.com',
                'password' => Hash::make('grillandchill1234'),
                'tourns' => [
                    'turno0' => ['start' => '09:00', 'end' => '12:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '13:00', 'end' => '16:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:00', 'end' => '23:00', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'El Rincón del Gourmet',
                'address' => 'Plaza Mayor, 1',
                'postal_code' => '47001',
                'location' => 'Valladolid',
                'category' => 'Gourmet',
                'tel_num' => '983123456',
                'email' => 'rincongourmet@gmail.com',
                'password' => Hash::make('rincongourmet1234'),
                'tourns' => [
                    'turno0' => ['start' => '08:30', 'end' => '11:30', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '13:00', 'end' => '16:00', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '19:00', 'end' => '22:30', 'tourn_name' => 'Cena']
                ]
            ],
            [
                'name' => 'Taco Fiesta',
                'address' => 'Calle Alcalá, 20',
                'postal_code' => '28014',
                'location' => 'Madrid',
                'category' => 'Mexicana',
                'tel_num' => '914567890',
                'email' => 'tacofiesta@gmail.com',
                'password' => Hash::make('tacofiesta1234'),
                'tourns' => [
                    'turno0' => ['start' => '09:00', 'end' => '12:00', 'tourn_name' => 'Desayuno'],
                    'turno1' => ['start' => '12:30', 'end' => '15:30', 'tourn_name' => 'Comida'],
                    'turno2' => ['start' => '18:00', 'end' => '22:00', 'tourn_name' => 'Cena']
                ]
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::factory()->create($restaurant);
        }
    }
}
