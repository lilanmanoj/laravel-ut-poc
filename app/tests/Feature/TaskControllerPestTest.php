<?php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Task API', function () {
    test('it lists tasks', function () {
        Task::factory()->count(5)->create();

        $this->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(5, 'data');
    });

    test('it creates a task', function () {
        $data = [
            'title' => 'New API Task',
            'description' => 'Test desc',
            'status' => 'pending',
            'due_date' => now()->addDay()->toDateString(),
        ];

        $this->postJson('/api/tasks', $data)
            ->assertCreated();

        $this->assertDatabaseHas('tasks', ['title' => 'New API Task']);
    });

    test('it can show a single task', function () {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertOk()
            ->assertJson(['id' => $task->id]);
    });

    test('it can update task via api', function () {
        $task = Task::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'status' => 'completed'
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('tasks', ['status' => 'completed']);
    });

    test('can delete task via api', function () {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    });
});
