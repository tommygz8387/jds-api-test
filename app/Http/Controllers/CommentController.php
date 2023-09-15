<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $comment = Comment::where('user_id',$user->id)
        ->with(['author','posted'])->paginate(10);
        return CommentResource::collection($comment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentCreateRequest $request)
    {
        $data = $request->validated();
        $author = Auth::user();
        $data['user_id'] = $author->id;

        $comment = Comment::create($data);
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $user = Auth::user();
        $comment = Comment::where('user_id',$user->id)->with('author')->find($id);
        if (!$comment) {
            return response()->json([
                'errors' => [
                    'message'=>[
                        'comment not found'
                    ]
                ]
            ])->setStatusCode(404);
        }
        return (new CommentResource($comment))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentUpdateRequest $request, int $id) : JsonResponse
    {
        $data = $request->validated();
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
        return (new CommentResource($update))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = Auth::user();
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

        $comment->delete();
        return response()->json([
            'data' => true
        ]);
    }
}
