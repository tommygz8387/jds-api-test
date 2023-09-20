<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'toms',
            'email' => 'toms@mail.com',
            'password' => Hash::make('password'),
            'remember_token' => 'test',
            'role' => 'admin'
        ]);
    }
}
