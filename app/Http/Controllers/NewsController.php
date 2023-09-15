<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\NewsResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Requests\NewsCreateRequest;
use App\Http\Requests\NewsUpdateRequest;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with('author')->paginate(10);
        return NewsResource::collection($news);
    }

    /**
     * Display a listing of the my resource.
     */
    public function getMyNews()
    {
        $user = Auth::user();
        $news = News::where('user_id',$user->id)->get();
        
        if ($news->count()==0) {
            return response()->json([
                'data' => [
                    'news not found'
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
        $author = Auth::user();
        $data['user_id'] = $author->id;

        $photo = $data['photo'];
        $str = Str::random(12);
        $getExtension = $photo->getClientOriginalExtension();
        $namaFile = $str.'.'.$getExtension;
        $photo->move('NewsPhoto', $namaFile);

        $news = News::create(array_merge($data, ['photo' => $namaFile]));
        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id) : JsonResponse
    {
        $news = News::with('author')->find($id);
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
    public function update(NewsUpdateRequest $request, int $id) : JsonResponse
    {
        $data = $request->validated();
        $user = Auth::user();
        $data = array_filter($data);
        $news = News::where('id',$id)->where('user_id',$user->id)->first();
        if (!$news) {
            return response()->json([
                'errors' => [
                    'message'=>[
                        'news not found'
                    ]
                ]
            ])->setStatusCode(404);
        }

        if (isset($data['photo'])) {
            if ($data['photo']->hasFile('NewsPhoto')) {
                $path = 'NewsPhoto/' . $news->photo;
                if (File::exists($path)) {
                    File::delete($path);
                }
    
                $photo = $data['photo'];
                $str = Str::random(12);
                $getExtension = $photo->getClientOriginalExtension();
                $namaFile = $str.'.'.$getExtension;
                $photo->move('NewsPhoto', $namaFile);
            }else{
                $namaFile = $news->photo;
            }
            $input = array_merge($data,['NewsPhoto'=>$namaFile]);
        }else{
            $input = $data;
        }


        $update = News::updateOrCreate(['id'=>$id],$input);

        return (new NewsResource($update))->response()->setStatusCode(200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id) : JsonResponse
    {
        $user = Auth::user();
        $news = News::where('id',$id)->where('user_id',$user->id)->first();

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
