<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantCollection;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RestaurantController extends Controller
{
    public function index()
    {

        $restaurants = Restaurant::query()->allowedFilters(['name','location','postal_code'])->get();
        return RestaurantCollection::make($restaurants);
    }

    public function show(Restaurant $restaurant)
    {
        return RestaurantResource::make($restaurant);
    }

    public function login(Request $request)
    {

        $request->validate([

            'email' => ['required','email'],
            'password' => ['required'],
            'device_name' => ['required']

        ]);

        $restaurant = Restaurant::whereEmail($request->email)->first();

        if(! $restaurant || ! Hash::check($request->password,$restaurant->password)){

            throw ValidationException::withMessages([

                'email' => 'Error al iniciar la sesion del restaurante'

            ]);


        }
        $token = $restaurant->createToken($request->device_name,['index-employees','show-employees','update-designs','create-designs']);
        $plainTextToken = $token->plainTextToken;


        return RestaurantResource::make($restaurant)->additional([

            'token' => $plainTextToken
        ]);

    }

    public function destroy(Restaurant $restaurant)
    {

        $restaurant->delete();

        return response()->noContent();

    }

    public function update(Restaurant $restaurant,Request $request)
    {

        $restaurant->update([

            'name' => $request->input('data.attributes.name'),
            'email' => $request->input('data.attributes.email'),
            'tel_num' => $request->input('data.attributes.tel_num'),
            'address' => $request->input('data.attributes.address'),
            'location' => $request->input('data.attributes.location'),
            'postal_code' => $request->input('data.attributes.postal_code'),
            'category' => $request->input('data.attributes.category'),
            'tourns' => $request->input('data.attributes.tourns'),
            'capacity' => $request->input('data.attributes.capacity'),
            
        ]);

        return RestaurantResource::make($restaurant);

    }

 
}
