<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Task;
use App\Services\TaskService;
use Carbon\Carbon;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_markOverdue_updates_only_overdue_incomplete_tasks_and_returns_count()
    {
        // overdue & incomplete -> should be updated
        $overdueIncomplete = Task::factory()->create([
            'due_date' => Carbon::now()->subDays(2),
            'is_completed' => false,
            'status' => 'pending',
        ]);

        // overdue but completed -> should NOT be updated
        $overdueCompleted = Task::factory()->create([
            'due_date' => Carbon::now()->subDays(3),
            'is_completed' => true,
            'status' => 'pending',
        ]);

        // future & incomplete -> should NOT be updated
        $futureIncomplete = Task::factory()->create([
            'due_date' => Carbon::now()->addDays(5),
            'is_completed' => false,
            'status' => 'pending',
        ]);

        $service = new TaskService();

        $updatedCount = $service->markOverdue();

        // Only the first task should be counted
        $this->assertEquals(1, $updatedCount);

        // Refresh models from DB
        $overdueIncomplete->refresh();
        $overdueCompleted->refresh();
        $futureIncomplete->refresh();

        $this->assertEquals('expired', $overdueIncomplete->status);
        $this->assertEquals('pending', $overdueCompleted->status);
        $this->assertEquals('pending', $futureIncomplete->status);
    }
}
