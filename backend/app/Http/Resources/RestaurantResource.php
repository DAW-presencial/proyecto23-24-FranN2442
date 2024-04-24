<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->token){

            return [

                'type' => 'restaurants',
                'id' =>  (string) $this->resource->getRouteKey(),
                'attributes' => [

                    'name' => $this->resource->name,
                    'address' => $this->resource->address,
                    'category' => $this->resource->category,
                    'location' => $this->resource->location,
                    'postal_code' => $this->resource->postal_code,
                    'tel_num' => $this->resource->tel_num,
                    'email' => $this->resource->email,
                    'tourns' => $this->resource->tourns,
                    'capacity' => $this->resource->capacity,

                ],
                'links' => [

                    'self' => route('api.v1.restaurants.show', $this->resource)

                ],
                "meta" => [

                    "token" => $request->token

                ]

            ];

        }
        return [

            'type' => 'restaurants',
            'id' =>  (string) $this->resource->getRouteKey(),
            'attributes' => [

                'name' => $this->resource->name,
                'address' => $this->resource->address,
                'category' => $this->resource->category,
                'location' => $this->resource->location,
                'postal_code' => $this->resource->postal_code,
                'tel_num' => $this->resource->tel_num,
                'email' => $this->resource->email,
                'tourns' => $this->resource->tourns,
                'capacity' => $this->resource->capacity,

            ],
            'links' => [

                'self' => route('api.v1.restaurants.show', $this->resource)

            ]
        ];
    }

    public function toResponse($request){

        return parent::toResponse($request)->withHeaders([

            'Location' => route('api.v1.restaurants.show',$this->resource)

        ]);

    }
}
