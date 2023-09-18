<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicUserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'news' => $this->whenLoaded('mypost', function(){
                return $this->mypost->map(function($news){
                    return new NewsResource($news);
                });
            }),
            'comment' => $this->whenLoaded('mycomments', function(){
                return $this->mycomments->map(function($comment){
                    return new CommentResource($comment);
                });
            })
        ];
    }
}
