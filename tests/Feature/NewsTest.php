<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\News;
use Database\Seeders\NewsSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
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

    public function testGetNewsSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->get('/api/news/'.$news->id,[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'title'=>'ini contoh judul',
                'photo'=> 'avatar.jpg',
                'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            ]
        ]);
    }
    public function testGetNewsNotFound(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->get('/api/news/'.($news->id+1),[
            'Authorization'=>'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'news not found'
                ]
            ]
        ]);
    }

    public function testUpdateTitleSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $oldNews = News::first();
        $this->patch('/api/news/'.$oldNews->id,[
            'title'=>'ini contoh judul baru',
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'title'=>'ini contoh judul baru',
                'photo'=> 'avatar.jpg',
                'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            ]
        ]);
        $newNews = News::where('title','ini contoh judul baru')->first();
        self::assertNotEquals($oldNews->title,$newNews->title);
    }
    public function testUpdateContentSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $oldNews = News::first();
        $this->patch('/api/news/'.$oldNews->id,[
            'content'=>'ini contoh content baru awkkwkwkwkwkwk',
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'title'=>'ini contoh judul',
                'photo'=> 'avatar.jpg',
                'content'=>'ini contoh content baru awkkwkwkwkwkwk',
            ]
        ]);
        $newNews = News::where('title','ini contoh judul')->first();
        self::assertNotEquals($oldNews->content,$newNews->content);
    }
    public function testUpdateUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->patch('/api/news/'.$news->id,[
            'content'=>'ini contoh content baru',
        ],[
            'Authorization'=>'test2'
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
    public function testDeleteSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->delete('/api/news/'.$news->id, headers: [
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>true
        ]);
    }
    public function testDeleteNotFound(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->delete('/api/news/'.($news->id+2), headers: [
            'Authorization'=>'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'news not found'
                ]
            ]
        ]);
    }
    public function testDeleteUnauthorize(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->delete('/api/news/'.$news->id,[
            'Authorization'=>'test2'
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
