<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function index()
    {

        $reservations = Reservation::query()->allowedFilters(['restaurant_id','user_id','date','table_number','hour'])->get();

        return ReservationCollection::make($reservations);


    }
    public function show(Reservation $reservation)
    {
        return ReservationResource::make($reservation);
    }

    public function store(Request $request)
    {

        $reservation = Reservation::create([

            'reservation_code' => $request->input('data.attributes.reservation_code'),
            'date' => $request->input('data.attributes.date'),
            'hour' => $request->input('data.attributes.hour'),
            'diners' => $request->input('data.attributes.diners'),
            'table_number' => $request->input('data.attributes.table_number'),
            'user_id' => $request->input('data.attributes.user_id'),
            'restaurant_id' => $request->input('data.attributes.restaurant_id'),
            'design_id' => $request->input('data.attributes.design_id')

        ]);

        return ReservationResource::make($reservation);


    }

    public function destroy(Reservation $reservation)
    {

        $reservation->delete();

        return response()->noContent();

    }
}
