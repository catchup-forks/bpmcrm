<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use App\Model\Process;

class TaskController extends Controller
{

    /**
     * Get the list task
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Process $process)
    {
        return view('processes.tasks.index', ['process' => $process]);
    }
}
