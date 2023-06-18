<?php

namespace App\Http\Controllers\Designer;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Model\Process;

class ProcessController extends Controller
{

    /**
     * Get the list task
     *
     * @return Factory|View
     */
    public function index()
    {
        return view('processes.index');
    }


    /**
     * Redirects to the view of the designer
     *
     * @param string $process
     *
     * @return Factory|View
     */
    public function show($process = null)
    {
        $model = Process::where('uid', '=', $process)->first();

        if (!$model) {
            request()->session()->flash('_alert', json_encode(['danger', __('The process was not found.')]));
            return redirect('processes');
        }
        $title = $model->name;
        return view('designer.designer', ['process' => $model, 'title' => $title]);
    }
}
