<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Http\Resources\NewsResource;

class PublicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getNews()
    {
        $news = News::with('author')->paginate(10);
        return NewsResource::collection($news);
    }

    public function getNewsDetail(String $id)
    {
        $news = News::where('id',$id)->with(['author','comments'])->get();
        dd($news);
        return (new NewsResource($news))->response()->setStatusCode(200);
    }
}
