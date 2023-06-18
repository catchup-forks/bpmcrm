<?php

namespace App\Http\Controllers\Process;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Models\Screen;

final class ScreenController extends Controller
{
    /**
     * Get the list of screens
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('processes.screens.index');
    }

    /**
     * Get page edit
     *
     *
     * @return Factory|View
     */
    public function edit(Screen $screen)
    {
        return view('processes.screens.edit', compact('screen'));
    }
}
