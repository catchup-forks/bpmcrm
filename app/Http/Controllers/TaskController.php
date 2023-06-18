<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProcessRequestToken;

final class TaskController extends Controller
{
    private static array $dueLabels = [
        'open' => 'Due ',
        'completed' => 'Completed ',
        'overdue' => 'Due ',
    ];

    public function index()
    {
        return view('tasks.index');
    }

    public function show()
    {
        return view('tasks.show');
    }

    public function edit(ProcessRequestToken $task)
    {
        return view('tasks.edit', ['task' => $task, 'dueLabels' => self::$dueLabels]);
    }
}
