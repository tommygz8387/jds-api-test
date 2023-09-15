<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{    public function getUser(Request $request): UserResource
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
}
