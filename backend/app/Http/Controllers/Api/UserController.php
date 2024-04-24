<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    public function login(Request $request)
    {

        $request->validate([

            'email' => ['required','email'],
            'password' => ['required'],
            'device_name' => ['required']

        ]);

        $user = User::whereEmail($request->email)->first();

        if(! $user || ! Hash::check($request->password,$user->password)){

            throw ValidationException::withMessages([

                'email' => 'Error al iniciar la sesion'

            ]);


        }
        $token = $user->createToken($request->device_name,['update-users','delete-users','show-users','create-reservations','index-reservations','show-reservations']);
        $plainTextToken = $token->plainTextToken;


        return UserResource::make($user)->additional([

            'token' => $plainTextToken
        ]);

    }

    public function store(Request $request)
    {

        $request->validate([

            'data.attributes.full_name' => ['required','string'],
            'data.attributes.email' => ['required','email'],
            'data.attributes.password' => ['required','string'],
            'data.attributes.tel_num' => ['required','string']

        ]);

        $user = User::create([

            'full_name' => $request->input('data.attributes.full_name'),
            'email' => $request->input('data.attributes.email'),
            'password' => $request->input('data.attributes.password'),
            'tel_num' => $request->input('data.attributes.tel_num'),

        ]);

        return UserResource::make($user);


    }

    public function index()
    {

        $users = User::all();

        return UserCollection::make($users);

    }

    public function update(User $user,Request $request)
    {

        $user->update([

            'full_name' => $request->input('data.attributes.full_name'),
            'email' => $request->input('data.attributes.email'),
            'password' => $request->input('data.attributes.password'),
            'tel_num' => $request->input('data.attributes.tel_num'),


        ]);

        return UserResource::make($user);

    }

    public function destroy(User $user)
    {

        $user->delete();

        return response()->noContent();

    }


}
