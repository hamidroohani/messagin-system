<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
             'name' => 'Pavel Durov',
             'email' => 'pavel.durov@example.com',
             'password' => Hash::make("123456"),
         ]);

        \App\Models\User::factory()->create([
            'name' => 'Mark Zuckerberg',
            'email' => 'mark.zuckerberg@example.com',
            'password' => Hash::make("123456"),
        ]);
    }
}
