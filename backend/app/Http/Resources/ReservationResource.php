<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [

            'type' => 'reservations',
            'id' =>  (string) $this->resource->getRouteKey(),
            'attributes' => [

                'reservation_code' => $this->resource->reservation_code,
                'date' => $this->resource->date,
                'hour' => $this->resource->hour,
                'old_hour' => $this->resource->old_hour,
                'diners' => $this->resource->diners,
                'table_number' => $this->resource->table_number,
                'user_id' => $this->resource->user->id,
                'restaurant_id' => $this->resource->restaurant->id,
                'design_id' => $this->resource->design->id

            ],
            'links' => [

                'self' => route('api.v1.reservations.show', $this->resource)

            ]
        ];
    }

    public function toResponse($request){

        return parent::toResponse($request)->withHeaders([

            'Location' => route('api.v1.reservations.show',$this->resource)

        ]);

    }
}
