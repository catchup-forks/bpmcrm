<?php

namespace App\Http\Controllers\Process;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

final class DocumentController extends Controller
{
    /**
     * Get the list of environment variables
     *
     * @return View|\Illuminate\Contracts\View
     */
    public function index()
    {
        return view('processes.documents.index');
    }
}
