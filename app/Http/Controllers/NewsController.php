<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use App\Exceptions\ifExistException;
use App\Http\Resources\NewsResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }
    /**
     * Display logged news list.
     */
    public function index()
    {
        try{
            $user = Auth::user();
            $news = $this->newsService->index($user);
        }catch(ifExistException $e){
            return response()->json([
                'data' => [
                    $e->getMessage()
                ]
            ])->setStatusCode(404);
        }
        return NewsResource::collection($news);
    }

    /**
     * Create news by logged user.
     */
    public function store(NewsCreateRequest $request) : NewsResource
    {
        $data = $request->validated();
        
        $news = $this->newsService->store($data);

        return new NewsResource($news);
    }

    /**
     * Display logged news by id + comments.
     */
    public function show(String $id) : JsonResponse
    {
        try{
            $user = Auth::user();
            $news = $this->newsService->show($user,$id);
        }catch(ifExistException $e){
            return response()->json([
                'errors' => [
                    'message'=>[
                        $e->getMessage()
                    ]
                ]
            ])->setStatusCode(404);
        }
        return (new NewsResource($news))->response()->setStatusCode(200);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(NewsUpdateRequest $request, String $id) : JsonResponse
    {
        try{
            $data = $request->validated();
            $update = $this->newsService->update($data,$id);
        }catch(ifExistException $e){
            return response()->json([
                'errors' => [
                    'message'=>[
                        $e->getMessage()
                    ]
                ]
            ])->setStatusCode(404);
        }
        

        return (new NewsResource($update))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(String $id) : JsonResponse
    {
        try{
            $user = Auth::user();
            $this->newsService->delete($user,$id);
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
