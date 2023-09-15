<?php

namespace App\Services;

use App\Models\News;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Exceptions\HttpResponseException;


class CommentService
{
    public function store($data)
    {
        $author = Auth::user();
        $data['user_id'] = $author->id;

        $comment = Comment::create($data);

        return $comment;
    }

    public function update($data,$id)
    {
        $user = Auth::user();
        $data = array_filter($data);
        $comment = Comment::where('id',$id)->where('user_id',$user->id)->first();
        if (!$comment) {
            return response()->json([
                'errors' => [
                    'message'=>[
                        'comment not found'
                    ]
                ]
            ])->setStatusCode(404);
        }

        $data['news_id'] = $comment->news_id;

        $update = Comment::updateOrCreate(['id'=>$id],$data);

        return $update;
    }
}