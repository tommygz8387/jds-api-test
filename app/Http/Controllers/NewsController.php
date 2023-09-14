<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Resources\NewsResource;
use App\Http\Requests\NewsCreateRequest;

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
     * Store a newly created resource in storage.
     */
    public function store(NewsCreateRequest $request) : NewsResource
    {
        $data = $request->validated();
        $news = News::create($data);
        return new NewsResource($news);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
