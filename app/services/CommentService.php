<?php

namespace App\Services;

use App\Models\Comment;
use App\Exceptions\ifExistException;
use Illuminate\Support\Facades\Auth;


class CommentService
{
    
    public function index($data)
    {
        $comment = Comment::where('user_id',$data->id)
        ->with('posted')->paginate(10);

        if ($comment->count()==0) {
            throw new ifExistException('no comment posted');
        }

        return $comment;
    }

    public function store($data)
    {
        $author = Auth::user();
        $data['user_id'] = $author->id;
        
        $comment = Comment::create($data);
        
        return $comment;
    }

    public function show($data,$id)
    {
        $comment = Comment::where('user_id',$data->id)->with('posted')->find($id);
        if (!$comment) {
            throw new ifExistException('comment not found');
        }

        return $comment;
    }

    public function update($data,$id)
    {
        $user = Auth::user();
        $data = array_filter($data);
        $comment = Comment::where('id',$id)->where('user_id',$user->id)->first();
        if (!$comment) {
            throw new ifExistException('comment not found');
        }

        $data['news_id'] = $comment->news_id;

        $update = Comment::updateOrCreate(['id'=>$id],$data);

        return $update;
    }
    
    public function delete($data,$id)
    {
        $comment = Comment::where('id',$id)->where('user_id',$data->id)->first();

        if (!$comment) {
            throw new ifExistException('comment not found');
        }

        $comment->delete();
        return $comment;
    }
}