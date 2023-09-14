<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request) : JsonResponse {
        $data = $request->validated();

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

        return (new UserResource($user))->response()->setStatusCode(201);
    }


    public function login(UserLoginRequest $request) : UserResource {
        $data = $request->validated();

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

        return new UserResource($user);
    }
    public function getUser(Request $request): UserResource
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function getUserById($id): UserResource
    {
        $user = User::find($id);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request) : UserResource
    {
        $data = $request->validated();
        $user = Auth::user();
        $dataToUpdate = [];

        if (isset($data['name'])) {
            $dataToUpdate['name'] = $data['name'];
        }

        if (isset($data['role'])) {
            $dataToUpdate['role'] = $data['role'];
        }

        if (isset($data['password'])) {
            $dataToUpdate['password'] = Hash::make($data['password']);
        }

        if (!empty($dataToUpdate)) {
            $user->update($dataToUpdate);
        }


        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function logout(Request $request) : JsonResponse
    {
        $user = Auth::user();
        $user->remember_token = null;
        $user->save();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}
