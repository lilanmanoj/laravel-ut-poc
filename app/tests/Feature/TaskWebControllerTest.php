<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;

class TaskWebControllerTest extends TestCase
{
    public function test_it_loads_task_list_view()
    {
        Task::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200)
                 ->assertViewIs('tasks.index')
                 ->assertViewHas('tasks');
    }

    public function test_view_displays_task_titles()
    {
        $task = Task::factory()->create(['title' => 'Sample Task']);

        $response = $this->get('/');
        $response->assertSee('Sample Task');
    }
}