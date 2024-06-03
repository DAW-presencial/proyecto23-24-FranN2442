<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {

        $employees = Employee::query()->allowedFilters(['restaurant_id','full_name'])->get();
        return EmployeeCollection::make($employees);

    }
    public function show(Employee $employee)
    {
        return EmployeeResource::make($employee);
    }

    public function login(Request $request)
    {

        $request->validate([

            'email' => ['required','email'],
            'pin' => ['required','string','min:4','max:4'],
            'device_name' => ['required'],

        ]);

        $employee = Employee::whereEmail($request->email)->first();

        if(! $employee || $request->pin != $employee->pin){

            throw ValidationException::withMessages([

                'email' => 'Error al iniciar la sesion'

            ]);


        }

        if($employee->role == 'admin'){

            $token = $employee->createToken($request->device_name,[ 'index-reservations','show-reservations','create-reservations','delete-reservations', // Reservations
                                                                    'update-employees','delete-employees','create-employees', // Employees
                                                                    'delete-restaurants','update-restaurants', // Restaurants
                                                                    'create-designs','delete-designs','update-designs']); // Designs

        } else {

            $token = $employee->createToken($request->device_name,['index-reservations','show-reservations','create-reservations','delete-reservations']);

        }

        $plainTextToken = $token->plainTextToken;

        return EmployeeResource::make($employee)->additional([

            'token' => $plainTextToken
        ]);

    }

    public function store(Request $request)
    {

        $request->validate([

            'data.attributes.full_name' => ['required'],
            'data.attributes.email' => ['required','email'],
            'data.attributes.pin' => ['required'],
            'data.attributes.tel_num' => ['required'],
            'data.attributes.role' => ['required'],
            'data.attributes.restaurant_id' => ['required']

        ]);

        $employee = Employee::factory()->create([


            'full_name' =>$request->input('data.attributes.full_name'),
            'email' => $request->input('data.attributes.email'),
            'pin' =>$request->input('data.attributes.pin'),
            'tel_num' => $request->input('data.attributes.tel_num'),
            'role' => $request->input('data.attributes.role'),
            'restaurant_id' => $request->input('data.attributes.restaurant_id')

        ]);

        return EmployeeResource::make($employee);

    }

    public function update(Employee $employee,Request $request)
    {

        $employee->update([

            'full_name' => $request->input('data.attributes.full_name'),
            'email' => $request->input('data.attributes.email'),
            'tel_num' => $request->input('data.attributes.tel_num'),
            'role' => $request->input('data.attributes.role'),


        ]);

        return EmployeeResource::make($employee);

    }

    public function destroy(Employee $employee)
    {

        $employee->delete();

        return response()->noContent();

    }

}
