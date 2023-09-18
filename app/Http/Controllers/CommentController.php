<?php

namespace App\Http\Controllers;

use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ifExistException;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CommentResource;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Display a list of logged comment.
     */
    public function index()
    {
        try{
            $user = Auth::user();
            $comment = $this->commentService->index($user);
        }catch(ifExistException $e){
            return response()->json([
                'data' => [
                    $e->getMessage()
                ]
            ])->setStatusCode(404);
        }
        return CommentResource::collection($comment);
    }

    /**
     * Store a newly created comment in storage.
     */
    public function store(CommentCreateRequest $request)
    {
        $data = $request->validated();
        
        $comment = $this->commentService->store($data);

        return new CommentResource($comment);
    }

    /**
     * Display the specified comment.
     */
    public function show(String $id)
    {
        try{
            $user = Auth::user();
            $comment = $this->commentService->show($user,$id);
        }catch(ifExistException $e){
            return response()->json([
                'errors' => [
                    'message'=>[
                        $e->getMessage()
                    ]
                ]
            ])->setStatusCode(404);
        }
        return (new CommentResource($comment))->response()->setStatusCode(200);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(CommentUpdateRequest $request, int $id) : JsonResponse
    {
        try{
            $data = $request->validated();
            $update = $this->commentService->update($data,$id);
        }catch(ifExistException $e){
            return response()->json([
                'errors' => [
                    'message'=>[
                        $e->getMessage()
                    ]
                ]
            ])->setStatusCode(404);
        }
        return (new CommentResource($update))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(String $id)
    {
        try{
            $user = Auth::user();
            $this->commentService->delete($user,$id);
        }catch(ifExistException $e){
            return response()->json([
                'errors' => [
                    'message'=>[
                        $e->getMessage()
                    ]
                ]
            ])->setStatusCode(404);
        }
        return response()->json([
            'data' => true
        ]);
    }
}
