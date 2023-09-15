<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(UserRegisterRequest $request) : JsonResponse {
        $data = $request->validated();

        $user = $this->authService->register($data);

        return (new UserResource($user))->response()->setStatusCode(201);
    }


    public function login(UserLoginRequest $request) : UserResource {
        $data = $request->validated();

        $user = $this->authService->login($data);

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