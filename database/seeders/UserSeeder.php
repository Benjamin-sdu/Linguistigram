<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     *  Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Steve',
            'email' => 'steve@example.com',
            'description' => 'Hello',
            'location' => 'US',
            'hobbies' => ['Fishing', 'Gaming', 'Watching movies'],
        ]);

        User::factory()->create([
            'name' => 'Mike',
            'email' => 'mike@example.com',
            'description' => 'Hi',
            'location' => 'GB',
            'hobbies' => ['Basketball', 'Reading', 'Gaming'],
        ]);

    }
}
