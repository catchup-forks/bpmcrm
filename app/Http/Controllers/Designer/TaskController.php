<?php

namespace App\Http\Controllers\Designer;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Model\Process;

final class TaskController extends Controller
{

    /**
     * Get the list task
     *
     * @return Factory|View
     */
    public function index(Process $process)
    {
        return view('processes.tasks.index', ['process' => $process]);
    }
}
