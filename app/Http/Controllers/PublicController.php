<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\User;
use App\Http\Resources\NewsResource;
use App\Http\Resources\PublicUserResource;

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
        return NewsResource::collection($news);
    }
    public function getUserProfile(String $id)
    {
        $user = User::where('id',$id)->with(['mypost','mycomments'])->get();
        return PublicUserResource::collection($user);
    }
}
