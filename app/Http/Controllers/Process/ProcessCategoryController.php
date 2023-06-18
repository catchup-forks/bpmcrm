<?php

namespace App\Http\Controllers\Process;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\ProcessCategory;

class ProcessCategoryController extends Controller
{
    /**
     * Get list of Process Categories
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('processes.categories.index');
    }

    /**
     * Get a specific process category
     *
     * @param ProcessCategory $processCategory
     *
     * @return Factory|View
     */
    public function edit(ProcessCategory $processCategory)
    {
        return view('processes.categories.edit', compact('processCategory'));
    }
}
