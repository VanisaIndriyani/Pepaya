<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
                'phone' => '6281234567890',
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User',
                'password' => 'password',
                'role' => 'user',
                'phone' => '628111222333',
            ]
        );

        $this->call([
            TanamanSeeder::class,
        ]);
    }
}
