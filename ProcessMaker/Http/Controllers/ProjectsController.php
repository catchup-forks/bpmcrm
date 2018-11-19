<?php

namespace ProcessMaker\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Models\ProcessRequestToken;

class ProjectsController extends Controller
{
    private static $dueLabels = [
        'open' => 'Due ',
        'completed' => 'Completed ',
        'overdue' => 'Due ',
    ];

    public function index()
    {
        return view('projects.index');
    }

    public function show()
    {
        return view('projects.show');
    }

    public function edit(ProcessRequestToken $project)
    {
        return view('projects.edit', ['project' => $project, 'dueLabels' => self::$dueLabels]);
    }
}
