<?php

namespace App\Http\Controllers;

use App\Models\Task;

class TaskWebController extends Controller
{
    public function index()
    {
        $tasks = Task::oldest()->paginate(10);

        return view('tasks.index', compact('tasks'));
    }
}
