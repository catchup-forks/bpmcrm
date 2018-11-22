<?php

namespace ProcessMaker\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Models\ProcessRequestToken;

class CrmController
{
    private static $dueLabels = [
        'open' => 'Due ',
        'completed' => 'Completed ',
        'overdue' => 'Due ',
    ];

    public function index()
    {
        return view('relations.index');
    }

    public function show()
    {
        return view('relations.show');
    }

    public function edit(ProcessRequestToken $relation)
    {
        return view('relations.edit', ['relation' => $relation, 'dueLabels' => self::$dueLabels]);
    }
}
