<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignCollection;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DesignController extends Controller
{
    public function index() : DesignCollection
    {

        $designs = Design::query()->allowedFilters(['restaurant_id'])->get();
        return DesignCollection::make($designs);
    }

    public function show(Design $design) : DesignResource
    {

        return DesignResource::make($design);
    }
    public function store(Request $request) : DesignResource
    {

        $request->validate([

            'data.attributes.hall_name' => ['required','string'],
            'data.attributes.tables' => ['required','json'],
            'data.attributes.restaurant_id' => ['required']

        ]);

        $design = Design::create([

            'hall_name' => $request->input('data.attributes.hall_name'),
            'tables' => $request->input('data.attributes.tables'),
            'restaurant_id' => $request->input('data.attributes.restaurant_id')

        ]);

        return DesignResource::make($design);

    }

    public function update(Request $request,Design $design) : DesignResource
    {

        $design->update([

            'hall_name' => $request->input('data.attributes.hall_name'),
            'tables' => $request->input('data.attributes.tables'),
            'restaurant_id' => $design->restaurant_id

        ]);

        return DesignResource::make($design);

    }

    public function destroy(Design $design) : Response
    {

        $design->delete();
        return response()->noContent();

    }

    public function confirmAssistance(Design $design,Request $request){

        $request->validate([

            "next" => ['required'],
            "actual" => ['required'],
            "table" => ['required']
        ]);

        // ! Mirar como hacer tener igualmente la hora antigua antes de actualizar la nueva en la reserva

        // $reservations = Reservation::query()->allowedFilters(['design_id','table_number','hour'])->get();

        // $reservation = $reservations[0];

        // $reservation->update([

        //      "hour" => $request->actual,
        //      "old_hour" => $request->next

        // ]);

        $tables = json_decode($design->tables,true);
        $nextIndex = array_search($request->next,$tables[$request->table]['ocupated_hours']);
        $tables[$request->table]['ocupated_hours'] = array_replace($tables[$request->table]['ocupated_hours'],array($nextIndex => $request->actual));
        $decoded_tables = json_encode($tables);
        
        $design->update([
            'hall_name' => $design->hall_name,
            'tables' =>  $decoded_tables,
            'restaurant_id' => $design->restaurant_id
        ]);

        return DesignResource::make($design);

    }
}
