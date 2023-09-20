<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('remember_token','test')->first();
        $news = News::where('user_id',$user->id)->first();
        Comment::create([
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            'user_id'=>$user->id,
            'news_id'=>$news->id,
        ]);
    }
}
