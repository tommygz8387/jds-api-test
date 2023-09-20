<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\News;
use App\Models\Comment;
use Database\Seeders\NewsSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CommentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    public function testCreateCommentSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->post('/api/comment',[
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            'news_id'=>$news->id,
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data'=>[
                'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            ]
        ]);
    }
    public function testCreateCommentUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class]);
        $news = News::first();
        $this->post('/api/comment',[
            'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            'news_id'=>$news->id,
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

    public function testGetMyCommentListSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $this->get('/api/comment/',[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                0=>['content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',]
            ]
        ]);
    }
    public function testGetMyCommentListUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $this->get('/api/comment/',[
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
    public function testGetMyCommentByIdSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->get('/api/comment/'.$comm->id,[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'content'=>'ini contoh content ajsdhkjahsdjahskdjhasd',
            ]
        ]);
    }
    public function testGetMyCommentByIdNotFound(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->get('/api/comment/'.($comm->id+1),[
            'Authorization'=>'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'comment not found'
                ]
            ]
        ]);
    }
    public function testGetMyCommentByIdUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->get('/api/comment/'.$comm->id,[
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

    public function testUpdateCommentSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $news = News::first();
        $oldComm = Comment::first();
        $this->patch('/api/comment/'.$oldComm->id,[
            'content'=>'ini contoh content baru ajsdhkjahsdjahskdjhasd',
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'content'=>'ini contoh content baru ajsdhkjahsdjahskdjhasd',
            ]
        ]);
        $newComm = Comment::where('news_id',$news->id)->first();
        self::assertNotEquals($oldComm->content,$newComm->content);
    }
    
    public function testUpdateCommentUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->patch('/api/comment/'.$comm->id,[
            'content'=>'ini contoh content baru ajsdhkjahsdjahskdjhasd',
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
    public function testUpdateCommentNotFound(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->patch('/api/comment/'.($comm->id+1),[
            'content'=>'ini contoh content baru ajsdhkjahsdjahskdjhasd',
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'comment not found'
                ]
            ]
        ]);
    }

    public function testDeleteCommentSuccess(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->delete('/api/comment/'.$comm->id, headers: [
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>true
        ]);
    }

    public function testDeleteCommentNotFound(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->delete('/api/comment/'.($comm->id+1), headers: [
            'Authorization'=>'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'comment not found'
                ]
            ]
        ]);
    }
    public function testDeleteCommentUnauthorized(): void
    {
        $this->seed([UserSeeder::class,NewsSeeder::class,CommentSeeder::class]);
        $comm = Comment::first();
        $this->delete('/api/comment/'.$comm->id, headers: [
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
