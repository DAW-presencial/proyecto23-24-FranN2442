<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationResource;
use App\Models\Design;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function index()
    {

        $reservations = Reservation::query()->allowedFilters(['restaurant_id','user_id','date','table_number','hour','old_hour','design_id'])->get();

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
            "old_hour" => null,
            'diners' => $request->input('data.attributes.diners'),
            'table_number' => $request->input('data.attributes.table_number'),
            'user_id' => $request->input('data.attributes.user_id'),
            'restaurant_id' => $request->input('data.attributes.restaurant_id'),
            'design_id' => $request->input('data.attributes.design_id')

        ]);

        return ReservationResource::make($reservation);


    }

    public function destroy(Reservation $reservation,Request $request)
    {
        $today = date('Y/m/d');

        

        if($reservation->date == $today){

            $reservation_hour = $reservation->hour;
    
            $designs = Design::query()->allowedFilters(['id'])->get();
            $design = $designs[0];

            
            $tables = json_decode($design->tables,true);
            
            foreach($tables as $key => $table){
                
                if($table["number"] == $request->table){
                    
    
                    $val_index = array_search($reservation_hour,$table["ocupated_hours"]);
                    $table["ocupated_hours"] = array_splice($table["ocupated_hours"],$val_index,$val_index);
                    $tables[$key] = $table;
    
                }
    
            }
    
            $design->update([
    
                "tables" => json_encode($tables)
    
            ]);

            dd($design);

        }

        $reservation->delete();

        return response()->noContent();

    }
}
