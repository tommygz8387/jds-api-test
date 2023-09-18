<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $news = News::where('user_id',$user->id)->paginate(10);
        
        if ($news->count()==0) {
            return response()->json([
                'data' => [
                    'no news posted'
                ]
            ])->setStatusCode(404);
        }
        return NewsResource::collection($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsCreateRequest $request) : NewsResource
    {
        $data = $request->validated();
        
        $news = $this->newsService->store($data);

        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id) : JsonResponse
    {
        $user = Auth::user();
        $news = News::where('user_id',$user->id)->with('comments')->find($id);
        if (!$news) {
            return response()->json([
                'errors' => [
                    'message'=>[
                        'news not found'
                    ]
                ]
            ])->setStatusCode(404);
        }
        return (new NewsResource($news))->response()->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NewsUpdateRequest $request, String $id) : JsonResponse
    {
        $data = $request->validated();
        
        $update = $this->newsService->update($data,$id);

        return (new NewsResource($update))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id) : JsonResponse
    {
        $user = Auth::user();
        $news = News::where('user_id',$user->id)->where('id',$id)->first();

        if (!$news) {
            return response()->json([
                'errors' => [
                    'message'=>[
                        'news not found'
                    ]
                ]
            ])->setStatusCode(404);
        }

        $news->delete();
        return response()->json([
            'data' => true
        ]);
    }
}
