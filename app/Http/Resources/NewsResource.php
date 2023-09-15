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
                return [
                    'id'=>$this->author->id,
                    'name'=>$this->author->name,
                    'email'=>$this->author->email,
                ];
            }),
            'comments' => $this->whenLoaded('comments', function () {
                return [
                    'id'=>$this->comments->id,
                    'content'=>$this->comments->content,
                ];
            }),
        ];
    }
}
