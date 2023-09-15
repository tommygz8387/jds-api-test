<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Exceptions\HttpResponseException;


class AuthService
{
    public function register($data)
    {
        if (User::where('email',$data['email'])->exists()) {
            throw new HttpResponseException(response([
                'errors'=> [
                    'email'=>[
                        'email already registered'
                    ]
                ]
                    ],400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function login($data)
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new HttpResponseException(response([
                'errors'=> [
                    'message'=>[
                        'invalid email or password'
                    ]
                ]
                    ],401));
        }

        $user->remember_token = Str::uuid()->toString();
        $user->save();

        return $user;
    }
}