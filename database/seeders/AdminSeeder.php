<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



class AdminSeeder extends Seeder
{
    protected static $password;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user= User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),


        ]);
        $user1 = User::create([
            'name' => 'yash',
            'email' => 'yash@gmail.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
        ]);
        $user->assignRole('admin');
        $user1->assignRole('user');
    }
}
