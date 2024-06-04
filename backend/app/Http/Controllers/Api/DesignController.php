<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignCollection;
use App\Http\Resources\DesignResource;
use App\Models\Design;
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

        $tables = $design->tables;
        $table = $tables->$request->table;
        
        dd($table);
        
        // $design->update([
        //     'hall_name' => $design->hall_name,
        //     'tables' =>  $decoded_tables,
        //     'restaurant_id' => $design->restaurant_id
        // ]);

    }
}
