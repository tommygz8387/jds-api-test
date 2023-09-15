<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{   
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
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
        
        $user = $this->userService->update($data);

        return new UserResource($user);
    }
}
