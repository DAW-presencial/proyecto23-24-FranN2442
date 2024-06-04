<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\RestaurantController;
use Illuminate\Http\Request;

// ! Restaurants API Routes without Token-----------------------------------------------------------------------------------------------
Route::get('restaurants',[RestaurantController::class,'index']) // * RESTAURANT INDEX
->name(('api.v1.restaurants.index')); // HECHO
Route::get('restaurants/{restaurant}',[RestaurantController::class,'show']) // * RESTAURANT SHOW
->name(('api.v1.restaurants.show')); // HECHO
Route::post('restaurant_login',[RestaurantController::class,'login']) // * RESTAURANT LOGIN
->name(('api.v1.restaurant.login')); // HECHO

// ! Employees API Routes without Token -------------------------------------------------------------------------------------------------

Route::post('employee_login',[EmployeeController::class,'login']) // * EMPLOYEE LOGIN
->name(('api.v1.employee.login')); // HECHO

// ! Users API Routes without Token-----------------------------------------------------------------------------------------------------

Route::post('user_login',[UserController::class,'login'])
->name(('api.v1.users.login')); // HECHO
Route::get('users',[UserController::class,'index'])
->name(('api.v1.users.index')); // HECHO
Route::post('users',[UserController::class,'store'])
->name(('api.v1.users.store')); // HECHO
Route::get('designs/{design}',[DesignController::class,'show'])
        ->name(('api.v1.designs.show'));
Route::get('designs',[DesignController::class,'index'])
        ->name(('api.v1.designs.index'));
Route::get('reservations',[ReservationController::class,'index'])
        ->name('api.v1.reservations.index'); // HECHO

// ! Abilities Group Authentication ---------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->patch('passwordReset/{user}',[UserController::class,'resetPassword'])->name('user.reset.password');

// ! Routes for Employees with role Admin can do:

Route::middleware(['auth:sanctum','abilities:update-employees,delete-employees,create-employees'])->group(function(){

    Route::post('employees',[EmployeeController::class,'store'])
        ->name('api.v1.employees.store'); // HECHO
    Route::patch('employees/{employee}',[EmployeeController::class,'update'])
        ->name('api.v1.employees.update'); // HECHO
    Route::delete('employees/{employee}',[EmployeeController::class,'destroy'])
        ->name('api.v1.employees.destroy'); // HECHO

});
        // ! Routes for where logged Users can do:
Route::middleware(['auth:sanctum','ability:update-users,delete-users,show-users,create-reservations'])->group(function(){

    Route::get('users/{user}',[UserController::class,'show'])
        ->name('api.v1.users.show'); // HECHO
    Route::patch('users/{user}',[UserController::class,'update'])
        ->name('api.v1.users.update'); // HECHO
    Route::delete('users/{user}',[UserController::class,'destroy'])
        ->name('api.v1.users.destroy'); // HECHO
    Route::post('reservations',[ReservationController::class,'store'])
        ->name('api.v1.reservations.store'); // HECHO

});
        // ! At least the User or Restaurant needs to have one of these:
Route::middleware(['auth:sanctum','abilities:index-reservations,show-reservations'])->group(function(){

    Route::get('reservations/{reservation}',[ReservationController::class,'show'])
    ->name('api.v1.reservations.show'); // HECHO
    Route::delete('reservations/{reservation}',[ReservationController::class,'destroy'])
    ->name('api.v1.reservations.destroy'); // HECHO

});

        // ! At least the Employee needs to have one of these:
Route::middleware(['auth:sanctum','ability:delete-restaurants,update-restaurants'])->group(function(){

    Route::delete('restaurants/{restaurant}',[RestaurantController::class,'destroy'])
        ->name('api.v1.restaurants.destroy');
    Route::patch('restaurants/{restaurant}',[RestaurantController::class,'update'])
        ->name('api.v1.restaurants.update');

});

Route::middleware(['auth:sanctum','abilities:index-employees,show-employees'])->group(function(){

    Route::get('employees/{employee}',[EmployeeController::class,'show'])
        ->name('api.v1.employees.show'); // HECHO
    Route::get('employees',[EmployeeController::class,'index'])
        ->name('api.v1.employees.index'); // HECHO

});

Route::middleware(['auth:sanctum','ability:create-designs,delete-designs,update-designs'])->group(function(){

    Route::post('designs',[DesignController::class,'store'])
        ->name(('api.v1.designs.store'));
    Route::patch('designs/{design}',[DesignController::class,'update'])
        ->name(('api.v1.designs.update'));
    Route::delete('designs/{design}',[DesignController::class,'destroy'])
        ->name(('api.v1.designs.destroy'));
    Route::patch('designs/confassis/{design}',[DesignController::class,'confirmAssistance'])
        ->name(('api.v1.designs.confassis'));

});


