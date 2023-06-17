<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;

class ProcessCategoryController extends Controller
{

    /**
     * Get the list task
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('processes.categories.index');
    }

}
