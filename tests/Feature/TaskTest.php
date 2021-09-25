<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $creator;
    protected User $assignee;
    protected TaskStatus $status;
    protected Task $task;
    protected Label $firstLabel;
    protected Label $secondLabel;
    protected Label $thirdLabel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->creator = User::factory()->create(); // @phpstan-ignore-line
        $this->assignee = User::factory()->create(); // @phpstan-ignore-line
        $this->status = TaskStatus::factory()->create(); // @phpstan-ignore-line
        // @phpstan-ignore-next-line
        $this->task = Task::factory()->create(
            [
                // @phpstan-ignore-next-line
                'status_id' => $this->status->id,
                // @phpstan-ignore-next-line
                'created_by_id' => $this->creator->id,
                // @phpstan-ignore-next-line
                'assigned_to_id' => $this->assignee->id,
            ]
        );
        $this->actingAs($this->creator); // @phpstan-ignore-line
        $this->firstLabel = Label::factory()->create(); // @phpstan-ignore-line
        $this->secondLabel = Label::factory()->create(); // @phpstan-ignore-line
        $this->thirdLabel = Label::factory()->create(); // @phpstan-ignore-line
    }

    public function testIndex(): void
    {
        $response = $this->get(route('task.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('task.show', ['id' => $this->status->id])); // @phpstan-ignore-line
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
                // @phpstan-ignore-next-line
                'status_id' => $this->status->id,
                // @phpstan-ignore-next-line
                'created_by_id' => $this->creator->id,
                // @phpstan-ignore-next-line
                'assigned_to_id' => $this->assignee->id,
            ]
        )->toArray();
        $data = Arr::only($factoryData, ['name', 'description', 'status_id', 'created_by_id', 'assigned_to_id']);
        $response = $this->post(route('task.store'), array_merge($data, ['labels' => [$this->firstLabel->id, $this->secondLabel->id]])); // @phpstan-ignore-line
        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', $data);
        $this->assertDatabaseHas(
            'label_task',
            // @phpstan-ignore-next-line
            ['label_id' => $this->firstLabel->id]
        );
        $this->assertDatabaseHas(
            'label_task',
            // @phpstan-ignore-next-line
            ['label_id' => $this->secondLabel->id]
        );
    }

    public function testEdit(): void
    {
        $response = $this->get(route('task.edit', ['id' => $this->task->id])); // @phpstan-ignore-line
        $response->assertStatus(200);
    }

    public function testUpdate(): void
    {
        $oldTask = $this->task->toArray();
        $newTask = Task::factory()->make(
            [
                // @phpstan-ignore-next-line
                'status_id' => $this->status->id,
                // @phpstan-ignore-next-line
                'created_by_id' => $this->creator->id,
                // @phpstan-ignore-next-line
                'assigned_to_id' => $this->assignee->id,
            ]
        )->toArray();
        $response = $this->patch(route('task.update', ['id' => $this->status->id]), array_merge($newTask, ['labels' => [$this->secondLabel->id, $this->thirdLabel->id]])); // @phpstan-ignore-line
        $response->assertStatus(302);
        $this->assertDatabaseHas('tasks', $newTask);
        $this->assertDatabaseMissing('tasks', $oldTask);

        $this->assertDatabaseMissing(
            'label_task',
            // @phpstan-ignore-next-line
            ['label_id' => $this->firstLabel->id]
        );
        $this->assertDatabaseHas(
            'label_task',
            // @phpstan-ignore-next-line
            ['label_id' => $this->secondLabel->id]
        );
        $this->assertDatabaseHas(
            'label_task',
            // @phpstan-ignore-next-line
            ['label_id' => $this->thirdLabel->id]
        );
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('task.destroy', ['id' => $this->task->id])); // @phpstan-ignore-line
        $response->assertStatus(302);
        $this->assertDatabaseMissing('tasks', $this->task->toArray());
    }
}
