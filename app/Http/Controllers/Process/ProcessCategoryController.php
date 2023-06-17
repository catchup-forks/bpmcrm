<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;
use App\Models\ProcessCategory;

class ProcessCategoryController extends Controller
{
    /**
     * Get list of Process Categories
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(ProcessCategory $processCategory)
    {
        return view('processes.categories.edit', compact('processCategory'));
    }
}
