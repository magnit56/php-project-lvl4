<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected Model $creator;
    protected Model $assignee;
    protected Model $status;
    protected Model $task;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creator = User::factory()->create();
        $this->assignee = User::factory()->create();
        $this->status = TaskStatus::factory()->create();
        $this->task = Task::factory()->create(
            [
                'status_id' => $this->status->id,
                'created_by_id' => $this->creator->id,
                'assigned_to_id' => $this->assignee->id,
            ]
        );
        $this->actingAs($this->creator);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('task.show', ['id' => $this->status->id]));
        $response->assertStatus(200);
    }

    public function testCreate(): void
    {
        $response = $this->get(route('task.create'));
        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $factoryData = Task::factory()->make(
            [
                'status_id' => $this->status->id,
                'created_by_id' => $this->creator->id,
                'assigned_to_id' => $this->assignee->id,
            ]
        )->toArray();
        $data = Arr::only($factoryData, ['name', 'description', 'status_id', 'created_by_id', 'assigned_to_id']);
        $response = $this->post(route('task.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', $data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('task.edit', ['id' => $this->task->id]));
        $response->assertStatus(200);
    }

    public function testUpdate(): void
    {
        $oldTask = $this->task->toArray();
        $newTask = Task::factory()->make(
            [
                'status_id' => $this->status->id,
                'created_by_id' => $this->creator->id,
                'assigned_to_id' => $this->assignee->id,
            ]
        )->toArray();
        $response = $this->patch(route('task.update', ['id' => $this->status->id]), $newTask);
        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', $newTask);
        $this->assertDatabaseMissing('tasks', $oldTask);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('task.destroy', ['id' => $this->task->id]));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tasks', $this->task->toArray());
    }
}