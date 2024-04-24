<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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

                'data' => [


                    'type' => 'employees',
                    'id' =>  (string) $this->resource->getRouteKey(),
                    'attributes' => [

                        'full_name' => $this->resource->full_name,
                        'email' => $this->resource->email,
                        'tel_num' => $this->resource->tel_num,
                        'role' => $this->resource->role,
                        'restaurant_id' => $this->resource->restaurant->id

                    ],
                    'links' => [

                        'self' => route('api.v1.employees.show', $this->resource)

                    ],
                ],
                "meta" => [

                    "token" => $request->token

                ]

            ];

        }

        return [

            'type' => 'employees',
            'id' =>  (string) $this->resource->getRouteKey(),
            'attributes' => [

                'full_name' => $this->resource->full_name,
                'email' => $this->resource->email,
                'tel_num' => $this->resource->tel_num,
                'role' => $this->resource->role,
                'restaurant_id' => $this->resource->restaurant->id

            ],
            'links' => [

                'self' => route('api.v1.employees.show', $this->resource)

            ]
        ];
    }

    public function toResponse($request){

        return parent::toResponse($request)->withHeaders([

            'Location' => route('api.v1.employees.show',$this->resource)

        ]);

    }
}
