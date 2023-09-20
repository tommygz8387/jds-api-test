<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('remember_token','test')->first();
        News::create([
            'title'=>'ini contoh judul',
            'photo'=> 'avatar.jpg',
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            'user_id'=>$user->id,
        ]);
    }
}
