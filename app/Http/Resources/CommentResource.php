<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created at' => $this->created_at,
            'author' => $this->whenLoaded('author', function () {
                return [
                    'id'=>$this->author->id,
                    'name'=>$this->author->name,
                    'email'=>$this->author->email,
                ];
            }),
            'parent' => $this->whenLoaded('posted', function () {
                return [
                    'id'=>$this->posted->id,
                    'title'=>$this->posted->title,
                ];
            }),
        ];
    }
}
