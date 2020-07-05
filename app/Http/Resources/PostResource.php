<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(type="object", required={"title", "content", "publication_date", "beginning", "end"})
 */
class PostResource extends JsonResource
{
    /**
     * @OA\Property(property="id", type="integer")
     * @OA\Property(property="title", type="string")
     * @OA\Property(property="content", type="string")
     * @OA\Property(property="publication_date", type="date", format="Y-m-d", example="2020-05-12")
     * @OA\Property(property="beginning", type="date", format="Y-m-d", example="2020-05-12")
     * @OA\Property(property="end", type="date", format="Y-m-d", example="2020-05-12")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'publication_date' => $this->publication_date,
            'beginning' => $this->beginning,
            'end' => $this->end,
            'image' => new PostImageResource($this->image)
        ];
    }
}
