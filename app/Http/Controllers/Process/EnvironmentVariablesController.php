<?php

namespace App\Http\Controllers\process;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\EnvironmentVariable;

class EnvironmentVariablesController extends Controller
{
    /**
     * Get the list of environment variables
     *
     * @return Factory|View
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
     * @return Factory|View
     */
    public function edit(EnvironmentVariable $environmentVariable)
    {
        return view('processes.environment-variables.edit', compact('environmentVariable'));
    }

}
