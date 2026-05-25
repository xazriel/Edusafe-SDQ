<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Guru BK',
            'email'    => 'gurubk@a.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Siswa Demo',
            'email'    => 'siswa@a.com',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);
    }
}