<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_list_of_tasks_via_api()
    {
        Task::factory()->count(5)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_create_task_via_api()
    {
        $data = [
            'title' => 'New API Task',
            'description' => 'Test desc',
            'status' => 'pending',
            'due_date' => now()->addDay()->toDateString(),
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'New API Task']);
    }

    public function test_can_show_a_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJson(['id' => $task->id]);
    }

    public function test_can_update_task_via_api()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'status' => 'completed'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['status' => 'completed']);
    }

    public function test_can_delete_task_via_api()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
