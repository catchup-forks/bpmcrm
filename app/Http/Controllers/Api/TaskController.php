<?php
namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Facades\WorkflowManager;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiCollection;
use App\Http\Resources\Task as Resource;
use App\Models\ProcessRequestToken;

final class TaskController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     *
     * @return Response
     */
    public function index(Request $request): ApiCollection
    {
        $query = ProcessRequestToken
            ::join('process_requests as request', 'request.id', '=', 'process_request_tokens.process_request_id')
            ->join('users as user', 'user.id', '=', 'process_request_tokens.user_id')
            ->select('process_request_tokens.*');
        $include  = $request->input('include') ? explode(',',(string) $request->input('include')) : [];
        $query->with($include);
        $filter = $request->input('filter', '');
        if (!empty($filter)) {
            $filter = '%' . $filter . '%';
            $query->where(function ($query) use ($filter): void {
                $query->Where('element_name', 'like', $filter)
                    ->orWhere('process_request_tokens.status', 'like', $filter)
                    ->orWhere('request.name', 'like', $filter)
                    ->orWhere('user.firstname', 'like', $filter)
                    ->orWhere('user.lastname', 'like', $filter);
            });
        }
        $filterByFields = ['process_id', 'user_id', 'process_request_tokens.status' => 'status', 'element_id', 'element_name', 'process_request_id'];
        $parameters = $request->all();
        foreach ($parameters as $column => $filter) {
            if (in_array($column, $filterByFields)) {
                $key = array_search($column, $filterByFields);
                $query->where(is_string($key) ? $key : $column, 'like', $filter);
            }
        }
        //list only display elements type task
        $query->where('element_type', '=', 'task');
        $query->orderBy(
            $request->input('order_by', 'updated_at'), $request->input('order_direction', 'asc')
        );
        $response = $query->paginate($request->input('per_page', 10));
        return new ApiCollection($response);
    }

    /**
     * Display the specified resource.
     *
     *
     * @return Resource
     */
    public function show(ProcessRequestToken $task): Resource
    {
        return new Resource($task);
    }

    /**
     * Updates the current element
     *
     *
     * @return Resource
     * @throws Throwable
     */
    public function update(Request $request, ProcessRequestToken $task)
    {
        if ($request->input('status') === 'COMPLETED') {
            if ($task->status === 'CLOSED') {
                return abort(422, __('Task already closed'));
            }
            $data = $request->input();
            //Call the manager to trigger the start event
            $process = $task->process;
            $instance = $task->processRequest;
            WorkflowManager::completeTask($process, $instance, $task, $data);
            return new Resource($task->refresh());
        }
        return abort(422);
    }
}
