<?php

namespace ProcessMaker\Http\Controllers;

use ProcessMaker\Http\Controllers\Controller;
use ProcessMaker\Models\ProcessRequestToken;

class TicketsController extends Controller
{
    private static $dueLabels = [
        'open' => 'Due ',
        'completed' => 'Completed ',
        'overdue' => 'Due ',
    ];

    public function index()
    {
        return view('tickets.index');
    }

    public function show()
    {
        return view('tickets.show');
    }

    public function edit(ProcessRequestToken $ticket)
    {
        return view('tickets.edit', ['ticket' => $ticket, 'dueLabels' => self::$dueLabels]);
    }
}
