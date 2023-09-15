<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'news_id' => $this->id,
            'title' => $this->title,
            'photo' => $this->photo,
            'content' => $this->content,
            'author' => $this->whenLoaded('author', function () {
                return new UserResource($this->author);
            }),
        ];
    }
}
