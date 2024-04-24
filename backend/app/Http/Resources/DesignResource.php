<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'type' => 'design',
            'id' =>  (string) $this->resource->getRouteKey(),
            'attributes' => [

                'hal_name' => $this->resource->hall_name,
                'tables' => $this->resource->tables,
                'restaurant_id' => $this->resource->restaurant_id,

            ],
            'links' => [

                'self' => route('api.v1.designs.show', $this->resource)

            ]
        ];
    }

    public function toResponse($request){

        return parent::toResponse($request)->withHeaders([

            'Location' => route('api.v1.designs.show',$this->resource)

        ]);

    }
}
