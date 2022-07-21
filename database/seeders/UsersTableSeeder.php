<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                "name" => "Administrator",
                "email" => "admin@admin.com",
                'username' => "admin",
                'role' => User::ADMINISTRATOR,
                'email_verified_at' => now(),
                "password" => bcrypt("password"),
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ],
            [
                "name" => "Secretary",
                "email" => "secretary@secretary.com",
                'username' => "secretary",
                'role' => User::SECRETARY,
                'email_verified_at' => now(),
                "password" => bcrypt("password"),
                'remember_token' => Str::random(10),
                'created_at' => now(),
            ]
        ]);
        User::factory(20)->create();
    }
}
