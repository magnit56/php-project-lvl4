<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskStatusTest extends TestCase
{
    use RefreshDatabase;

    protected Model $status;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->status = TaskStatus::factory()->create();
        $this->actingAs($user);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('taskStatus.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('taskStatus.show', ['id' => $this->status->id]));
        $response->assertStatus(403);
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
        $response = $this->get(route('taskStatus.edit', ['id' => $this->status->id]));
        $response->assertStatus(200);
    }

    public function testUpdate(): void
    {
        $id = $this->status->id;
        $oldTaskStatus = $this->status->toArray();
        $newTaskStatus = TaskStatus::factory()->make()->toArray();
        $response = $this->patch(route('taskStatus.update', ['id' => $id]), $newTaskStatus);
        $response->assertStatus(302);
        $this->assertDatabaseHas('task_statuses', $newTaskStatus);
        $this->assertDatabaseMissing('task_statuses', $oldTaskStatus);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('taskStatus.destroy', ['id' => $this->status->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('task_statuses', $this->status->toArray());
    }
}
