<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CrateUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function example(): void
    {
        $response = $this->postJson(route('api.v1.users.store'),[

            "data" => [

                "type" => "users",
                "attributes" => [

                    "full_name" => "Francisco",
                    "email" => "fran@gmail.com",
                    "password" => "fran1234",
                    "tel_num" =>  "626054421"

                ]
            ]
        ],  [
                'accept' => 'application/vnd.api+json',
                'content-type' => 'application/vnd.api+json'
            ]
        );

        $response->assertCreated();


    }
}
