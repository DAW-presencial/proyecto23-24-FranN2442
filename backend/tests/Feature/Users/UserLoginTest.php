<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_and_returns_token(): void
    {

        $user = User::factory()->create();

        $credentials = [

            'email' => 'fran04@gmail.com',
            'password' => 'password',
            'device_name' => 'my_device'

        ];

        $response = $this->getJson(route('api.v1.users.login',$credentials));

        $response->assertStatus(200);

    }

}
