<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Include a minimal Bootstrap for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f9fafb; padding: 2rem; font-family: 'Segoe UI', sans-serif; }
        table { background: white; border-radius: .5rem; overflow: hidden; }
        .table th { background: #f1f5f9; }
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-pending { background: #fff7e6; color: #b76e00; }
        .status-completed { background: #e6ffed; color: #1a7f37; }
        .status-expired { background: #ffe6e6; color: #b42318; }
    </style>
</head>
<body>

<div class="container-md container-sm-fluid">
    <h2 class="mb-4 text-center">📋 Task Manager</h2>

    @if ($tasks->count())
        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $index => $task)
                        <tr>
                            <td>{{ $tasks->firstItem() + $index }}</td>
                            <td class="text-start">{{ $task->title }}</td>
                            <td class="text-start" title="{{ $task->description }}">{{ Str::limit($task->description, 50) }}</td>
                            <td>{{ $task->due_date ? $task->due_date->format('Y-m-d') : '—' }}</td>
                            <td>
                                <span class="status-badge status-{{ $task->status }}">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info text-center">No tasks found.</div>
    @endif
</div>

</body>
</html>
