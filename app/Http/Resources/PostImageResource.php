<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(type="object")
 */
class PostImageResource extends JsonResource
{
    /**
     * @OA\Property(property="post_id", type="integer")
     * @OA\Property(property="path", type="string")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'path' => asset(Storage::url($this->path)),
        ];
    }
}
