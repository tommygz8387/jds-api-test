<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\News;
use Illuminate\Support\Str;
use App\Exceptions\ifExistException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class NewsService
{
    public function index($data)
    {
        $news = News::where('user_id',$data->id)->paginate(10);
        
        if ($news->count()==0) {
            throw new ifExistException('no news posted');
        }

        return $news;
    }
    public function store($data)
    {
        $author = Auth::user();
        $data['user_id'] = $author->id;
        $photo = $data['photo'];
        $str = Carbon::now()->format('Ymd_His');

        $getExtension = $photo->getClientOriginalExtension();
        $namaFile = $author->id.'.'.$str.'.'.$getExtension;
        $photo->move('NewsPhoto', $namaFile);

        $news = News::create(array_merge($data, ['photo' => $namaFile]));

        return $news;
    }

    public function show($data,$id)
    {
        $news = News::where('user_id',$data->id)->with('comments')->find($id);
        if (!$news) {
            throw new ifExistException('news not found');
        }

        return $news;
    }

    public function update($data,$id)
    {
        $user = Auth::user();
        $data = array_filter($data);
        $news = News::where('id',$id)->where('user_id',$user->id)->first();
        if (!$news) {
            throw new ifExistException('news not found');
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

        return $update;
    }

    public function delete($data,$id)
    {
        $news = News::where('id',$id)->where('user_id',$data->id)->first();

        if (!$news) {
            throw new ifExistException('news not found');
        }

        $news->delete();
        return $news;
    }
}