<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    protected Model $label;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->label = Label::factory()->create(); // @phpstan-ignore-line
        Label::factory()->create();
        $this->actingAs($user); // @phpstan-ignore-line
    }

    public function testIndex(): void
    {
        $response = $this->get(route('label.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('label.show', ['id' => $this->label->id])); // @phpstan-ignore-line
        $response->assertStatus(403);
    }

    public function testCreate(): void
    {
        $response = $this->get(route('label.create'));
        $response->assertStatus(200);
    }

    public function testStore(): void
    {
        $factoryData = Label::factory()->make()->toArray();
        $data = Arr::only($factoryData, ['name', 'description']);
        $response = $this->post(route('label.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('labels', $data);
    }

    public function testEdit(): void
    {
        $response = $this->get(route('label.edit', ['id' => $this->label->id])); // @phpstan-ignore-line
        $response->assertStatus(200);
    }

    public function testUpdate(): void
    {
        $id = $this->label->id; // @phpstan-ignore-line
        $oldLabel = $this->label->toArray();
        $newLabel = Label::factory()->make()->toArray();
        $response = $this->patch(route('label.update', ['id' => $id]), $newLabel);
        $response->assertStatus(302);
        $this->assertDatabaseHas('labels', $newLabel);
        $this->assertDatabaseMissing('labels', $oldLabel);
    }

    public function testDestroy(): void
    {
        $response = $this->delete(route('label.destroy', ['id' => $this->label->id])); // @phpstan-ignore-line
        $response->assertStatus(302);
        $this->assertDatabaseMissing('labels', $this->label->toArray());
    }
}
