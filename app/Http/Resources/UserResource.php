<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object")
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="integer")
     * @OA\Property(property="email", type="string")
     * @OA\Property(property="name", type="string")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
