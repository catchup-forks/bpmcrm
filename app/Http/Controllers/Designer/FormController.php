<?php

namespace App\Http\Controllers\Designer;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Model\Form;
use App\Model\Process;

class FormController extends Controller
{
    /**
     * Get the Definition form
     *
     * @param Process $process
     * @param Form $form
     *
     * @return Factory|View
     */
    public function show(Process $process = null, Form $form = null)
    {
        if ($process->id !== $form->process_id) {
            request()->session()->flash('_alert', json_encode(['danger', __('The form does not belong to process.')]));
            // @todo  This should actually redirect to designer url
            return view('designer.designer', compact('process'));
        }
        return view('designer.form', compact(['process', 'form']));
    }

}