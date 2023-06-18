<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ProcessRequest;

final class RequestController extends Controller
{
    /**
     * Get the list of requests.
     *
     * @return View|\Illuminate\Contracts\View
     */
    public function index($type = null)
    {
        //load counters
        $allRequest = ProcessRequest::count();
        $startedMe = ProcessRequest::startedMe(Auth::user()->id)->count();
        $inProgress = ProcessRequest::inProgress()->count();
        $completed = ProcessRequest::completed()->count();

        $title = 'My Requests';

        $types = ['all'=>'All Requests','in_progress'=>'Requests In Progress','completed'=>'Completed Requests'];

        if(array_key_exists($type,$types)){
          $title = $types[$type];
        }

        return view('requests.index', compact(
            ['allRequest', 'startedMe', 'inProgress', 'completed', 'type','title']
        ));
    }

    /**
     * Request Show
     *
     *
     * @return Factory|View
     */
    public function show(ProcessRequest $request)
    {
        $request->participants;
        $request->user;
        $request->summary = $request->summary();
        $request->process->summaryScreen;
        return view('requests.show', compact('request'));
    }
}
