<?php

namespace Tests\Feature\Employees;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListEmployeesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_fetch_a_single_employee(): void
    {
        $this->withoutExceptionHandling();

        $employee = Employee::factory()->create();

        $response = $this->getJson(route('api.v1.employees.show', $employee));

        $response->assertExactJson([

            'data' => [

                'type' => 'employees',
                'id' =>  (string) $employee->getRouteKey(),
                'attributes' => [

                    'full_name' => $employee->full_name,
                    'email' => $employee->email,
                    'password' => $employee->password,
                    'tel_num' => $employee->tel_num,
                    'role' => $employee->role,
                    'restaurant_id' => $employee->restaurant->id

                ],
                'links' => [

                    'self' => route('api.v1.employees.show', $employee)

                ]

            ]

        ]);
    }
}
