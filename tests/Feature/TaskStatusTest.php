<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->seed(TaskStatusSeeder::class);
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('taskStatus.index'));
        $response->assertOk();
    }

    public function testCreate(): void
    {
        $response = $this->get(route('taskStatus.create'));
        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $factoryData = TaskStatus::factory()->make()->toArray();
        $data = Arr::only($factoryData, ['name']);
        $response = $this->post(route('taskStatus.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('task_statuses', $data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('taskStatus.edit', ['id' => 1]));
        $response->assertStatus(200);
    }

    public function testUpdate(): void
    {
        $oldTaskStatus = TaskStatus::findOrFail(1)->toArray();
        $newTaskStatus = TaskStatus::factory()->make()->toArray();
        $response = $this->patch(route('taskStatus.update', ['id' => 1]), $newTaskStatus);
        $response->assertStatus(302);
        $this->assertDatabaseHas('task_statuses', $newTaskStatus);
        $this->assertDatabaseMissing('task_statuses', $oldTaskStatus);
    }
}
