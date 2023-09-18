<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;
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
    
    public function testGetUserSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/users/current',[
            'Authorization'=>'test'
        ])->assertStatus(200)
        ->assertJson([
            'data'=>[
                'email'=>'toms@mail.com',
                'name'=>'toms',
            ]
        ]);
    }

    public function testGetUserUnauthorized(): void
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/users/current')
        ->assertStatus(401)
        ->assertJson([
            'errors'=>[
                'message'=>[
                    'unauthorized'
                    ]
            ]
        ]);
    }

    public function testGetUserInvalidToken(): void
    {
        $this->seed(UserSeeder::class);
        $this->get('/api/users/current',[
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

    public function testUpdatePasswordSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('email','toms@mail.com')->first();

        $this->patch('/api/users/current',[
            'password'=>'baru'
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'email'=>'toms@mail.com',
                'name'=>'toms',
            ]
        ]);

        $newUser = User::where('email','toms@mail.com')->first();
        self::assertNotEquals($oldUser->password,$newUser->password);
    }

    public function testUpdateNameSuccess(): void
    {
        $this->seed(UserSeeder::class);
        $oldUser = User::where('email','toms@mail.com')->first();

        $this->patch('/api/users/current',[
            'name'=>'namabaru'
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=>[
                'email'=>'toms@mail.com',
                'name'=>'namabaru',
            ]
        ]);

        $newUser = User::where('email','toms@mail.com')->first();
        self::assertNotEquals($oldUser->name,$newUser->name);
    }

    public function testUpdateFailed(): void
    {
        $this->seed(UserSeeder::class);

        $this->patch('/api/users/current',[
            'role'=>'asdasd'
        ],[
            'Authorization'=>'test'
        ])
        ->assertStatus(400)
        ->assertJson([
            'errors'=>[
                'role'=>[
                    "The selected role is invalid."
                ]
            ]
        ]);
    }

    public function testLogoutSuccess(): void
    {
        $this->seed(UserSeeder::class);

        $this->delete('/api/users/logout', headers: [
            'Authorization'=>'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data'=> true
        ]);

        $user = User::where('email','toms@mail.com')->first();
        self::assertNull($user->remember_token);
    }

    public function testLogoutFailed(): void
    {
        $this->seed(UserSeeder::class);

        $this->delete('/api/users/logout', headers: [
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
