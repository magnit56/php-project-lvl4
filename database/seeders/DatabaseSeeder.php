<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->create([
                'email' => 'magnit56@gmail.com',
                'password' => '$2y$10$f.IniLL73GzRXe7BIxkEyOj8jOJMyJwy1zYGt2BV7QtOOjbXDRpea',
            ]);
        User::factory()->count(10)->create();
    }
}
