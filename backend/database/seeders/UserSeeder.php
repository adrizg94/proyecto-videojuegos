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
        User::factory(30)->create();

        User::factory()->create([
            'username' => 'demo',
            'email' => 'demo@nextplay.test',
            'password' => Hash::make('password'),
        ]);
    }
}
