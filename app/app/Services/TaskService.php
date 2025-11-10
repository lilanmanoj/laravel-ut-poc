<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public function markOverdue(): int
    {
        return Task::where('due_date', '<', now())
            ->where('is_completed', false)
            ->update(['status' => 'expired']);
    }
}
