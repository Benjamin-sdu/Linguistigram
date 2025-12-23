<?php

namespace Database\Seeders;

use App\Models\Connection;
use Illuminate\Database\Seeder;

class ConnectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Connection::create([
            'sender_id' => 1,
            'receiver_id' => 2,
            'status' => 'accepted',
        ]);
    }
}
