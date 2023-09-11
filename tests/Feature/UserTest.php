<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotNull;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testRegisterSuccess(): void
    {
        $this->post('/api/users/register',[
            'name'=>'toms',
            'email'=>'toms@mail.com',
            'password'=>'password',
            'role'=>'admin',
        ])->assertStatus(201)
        ->assertJson([
            'data'=>[
                'name'=>'toms',
                'email'=>'toms@mail.com',
                'role'=>'admin',
            ]
        ]);
    }
    public function testRegisterFailed(): void
    {
        $this->post('/api/users/register',[
            'name'=>'',
            'email'=>'',
            'password'=>'',
            'role'=>'asdasd',
        ])->assertStatus(400)
        ->assertJson([
            'errors'=>[
                'name'=>['The name field is required.'],
                'email'=>['The email field is required.'],
                'password'=>['The password field is required.'],
            ]
        ]);
    }
    public function testRegisterEmailExists(): void
    {
        $this->testRegisterSuccess();
        $this->post('/api/users/register',[
            'name'=>'toms',
            'email'=>'toms@mail.com',
            'password'=>'password',
            'role'=>'admin',
        ])->assertStatus(400);
    }


    public function testLoginSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login',[
            'email'=>'toms@mail.com',
            'password'=>'password',
        ])->assertStatus(200)
        ->assertJson([
            'data'=>[
                'email'=>'toms@mail.com',
                'name'=>'toms',
            ]
        ]);

        $user = User::where('email','toms@mail.com')->first();
        self::assertNotNull($user->remember_token);
    }

    public function testLoginFailedEmailNotFound(): void
    {
        $this->post('/api/users/login',[
            'email'=>'toms@mail.com',
            'password'=>'password',
        ])->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'invalid email or password'
                    ]
            ]
        ]);
    }

    public function testLoginFailedPasswordWrong(): void
    {
        $this->seed(UserSeeder::class);
        $this->post('/api/users/login',[
            'email'=>'toms@mail.com',
            'password'=>'salah',
        ])->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'invalid email or password'
                    ]
            ]
        ]);
    }
}
