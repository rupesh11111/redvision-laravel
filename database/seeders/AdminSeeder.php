<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'role' => 'admin'
        ], [
            'name' => "admin",
            'email' => "admin@admin.com",
            'password' => "12345678",
            'mobile' => "9876543210",
        ]);
    }
}
