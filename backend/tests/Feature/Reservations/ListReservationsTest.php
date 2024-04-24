<?php

namespace Tests\Feature\Reservations;

use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListReservationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_reservation(): void
    {
        $this->withoutExceptionHandling();

        $reservation = Reservation::factory()->create();

        $response = $this->getJson(route('api.v1.reservations.show', $reservation));

        $response->assertExactJson([

            'data' => [

                'type' => 'reservations',
                'id' =>  (string) $reservation->getRouteKey(),
                'attributes' => [

                    'date' => $reservation->date,
                    'reservation_code' => $reservation->reservation_code,
                    'state' => $reservation->state,
                    'table_number' => $reservation->table_number,
                    'user_id' => $reservation->user->id,
                    'restaurant_id' => $reservation->restaurant->id,
                    'design_id' => $reservation->design->id

                ],
                'links' => [

                    'self' => route('api.v1.reservations.show', $reservation)

                ]

            ]

        ]);
    }
}
