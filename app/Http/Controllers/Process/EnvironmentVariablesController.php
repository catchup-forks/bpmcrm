<?php

namespace App\Http\Controllers\process;

use App\Http\Controllers\Controller;
use App\Models\EnvironmentVariable;

class EnvironmentVariablesController extends Controller
{
    /**
     * Get the list of environment variables
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('processes.environment-variables.index');
    }

    /**
     * Get a specific Environment Variable
     *
     * @param EnvironmentVariable $environmentVariable
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(EnvironmentVariable $environmentVariable)
    {
        return view('processes.environment-variables.edit', compact('environmentVariable'));
    }

}
