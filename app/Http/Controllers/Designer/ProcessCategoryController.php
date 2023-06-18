<?php

namespace App\Http\Controllers\Designer;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

final class ProcessCategoryController extends Controller
{

    /**
     * Get the list task
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('processes.categories.index');
    }

}
