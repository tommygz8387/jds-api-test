<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;


class NewsService
{
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

    public function update($data,$id)
    {
        $user = Auth::user();
        $data = array_filter($data);
        $news = News::where('id',$id)->where('user_id',$user->id)->first();
        // dd($news);
        if ($news==null) {
            return 'ok';
        // }else{
        //     return 'ga oke';
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
}