<?php

namespace App\Http\Controllers\Designer;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Model\Script;
use App\Model\Process;

final class ScriptController extends Controller
{
    /**
     * Show the script editor
     *
     * @param Process|null $process
     * @param Form $form
     *
     * @return Factory|View
     */
    public function show(Process $process = null, Script $script = null)
    {
        if ($process->id !== $script->process_id) {
            request()->session()->flash('_alert', json_encode(['danger', __('The script does not belong to process.')], JSON_THROW_ON_ERROR));
            // @todo  This should actually redirect to designer url
            return view('designer.designer', compact('process'));
        }
        return view('designer.script', compact(['process', 'script']));
    }

}