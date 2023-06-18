<?php

namespace App\Http\Controllers\Process;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Script;

class ScriptController extends Controller
{
     /**
     * Get the list of environment variables
     *
     * @return View|\Illuminate\Contracts\View
     */
    public function index()
    {
        return view('processes.scripts.index');
    }

    public function edit(Script $script)
    {
        return view('processes.scripts.edit', ['script' => $script]);
    }
}
