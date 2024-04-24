<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

                'type' => 'users',
                'id' =>  (string) $this->resource->getRouteKey(),
                'attributes' => [

                    'full_name' => $this->resource->full_name,
                    'email' => $this->resource->email,
                    'tel_num' => $this->resource->tel_num

                ],
                'links' => [

                    'self' => route('api.v1.users.show', $this->resource)

                ],
                "meta" => [

                    "token" => $request->token

                ]

            ];

        }
        return [

            'type' => 'users',
            'id' =>  (string) $this->resource->getRouteKey(),
            'attributes' => [

                'full_name' => $this->resource->full_name,
                'email' => $this->resource->email,
                'tel_num' => $this->resource->tel_num

            ],
            'links' => [

                'self' => route('api.v1.users.show', $this->resource)

            ]
        ];
    }

    public function toResponse($request){

        return parent::toResponse($request)->withHeaders([

            'Location' => route('api.v1.users.show',$this->resource)

        ]);

    }
}
