<?php

namespace Database\Seeders;

use App\Enums\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'type' => UserType::TYPE_ADMIN->value
        ]);

        \App\Models\User::factory(100)->create([
            'type' => UserType::TYPE_ADMIN->value
        ]);
    }
}
