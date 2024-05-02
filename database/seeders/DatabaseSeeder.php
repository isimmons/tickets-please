<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        $testUser = User::factory()->create([
            'name' => 'Foobar',
            'email' => 'test@example.com',
        ]);

        Ticket::factory(100)
            ->recycle($users)
            ->create();

        Ticket::factory(10)
            ->recycle($testUser)
            ->create();

    }
}
