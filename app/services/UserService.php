<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function update($data)
    {
        $user = Auth::user();
        $newData = [];

        if (isset($data['name'])) {
            $newData['name'] = $data['name'];
        }

        if (isset($data['role'])) {
            $newData['role'] = $data['role'];
        }

        if (isset($data['password'])) {
            $newData['password'] = Hash::make($data['password']);
        }

        if (!empty($newData)) {
            $user->update($newData);
        }

        return $user;
    }
}