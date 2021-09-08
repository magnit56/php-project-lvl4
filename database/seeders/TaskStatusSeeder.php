<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskStatus::factory()
            ->create([
                'name' => 'новый',
            ]);
        TaskStatus::factory()
            ->create([
                'name' => 'в работе',
            ]);
        TaskStatus::factory()
            ->create([
                'name' => 'на тестировании',
            ]);
        TaskStatus::factory()
            ->create([
                'name' => 'завершен',
            ]);
    }
}
