<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\News;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewsTest extends TestCase
{
    use RefreshDatabase;
    public function testCreateNewsSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/news',[
            'title'=>'ini contoh judul',
            'photo'=> UploadedFile::fake()->image('avatar.jpg'),
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data'=>[
                'title'=>'ini contoh judul',
                'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            ]
        ]);
        $news = News::where('title','ini contoh judul')->first();
        self::assertNotNull($news->photo);
    }
    public function testCreateUnauthorized(): void
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/news',[
            'title'=>'ini contoh judul',
            'photo'=> UploadedFile::fake()->image('avatar.jpg'),
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
        ],[
            'Authorization'=>'salah'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'unauthorized'
                ]
            ]
        ]);
    }


}
