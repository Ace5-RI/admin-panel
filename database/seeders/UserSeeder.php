<?php

namespace Database\Seeders;

use App\Models\User;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create(['name' => 'admin','role' =>'admin','status' => 'active', 'email' => 'admin@gmail.com','password' => Hash::make('password')]);
        User::create(['name' => 'staff','role' =>'staff','status' => 'active', 'email' => 'staff@gmail.com','password' => Hash::make('password')]);
        User::create(['name' => 'customer','role' =>'customer','status' => 'active', 'email' => 'customer@gmail.com','password' => Hash::make('password')]);
    }
}
