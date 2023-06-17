<?php
namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Process;
use App\Models\ProcessTaskAssignment;
use App\Models\User;
use Tests\TestCase;
use Tests\Feature\Shared\RequestHelper;

/**
 * Test the process execution with requests
 *
 * @group process_tests
 */
class ProcessCollaborationTest extends TestCase
{

    use WithFaker;
    use RequestHelper;

    /**
     *
     * @var User $user
     */
    protected $user;

    private $requestStructure = [
        'id',
        'process_id',
        'user_id',
        'status',
        'name',
        'initiated_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Create a single task process assigned to $this->user
     */
    private function createTestCollaborationProcess()
    {
        $process = factory(Process::class)->create([
            'bpmn' => Process::getProcessTemplate('Collaboration.bpmn')
        ]);
        //Assign the task to $this->user
        factory(ProcessTaskAssignment::class)->create([
            'process_id' => $process->id,
            'process_task_id' => '_5',
            'assignment_id' => $this->user->id,
            'assignment_type' => 'user',
        ]);
        factory(ProcessTaskAssignment::class)->create([
            'process_id' => $process->id,
            'process_task_id' => '_10',
            'assignment_id' => $this->user->id,
            'assignment_type' => 'user',
        ]);
        factory(ProcessTaskAssignment::class)->create([
            'process_id' => $process->id,
            'process_task_id' => '_24',
            'assignment_id' => $this->user->id,
            'assignment_type' => 'user',
        ]);
        return $process;
    }

    /**
     * Execute a process
     */
    public function testExecuteACollaboration()
    {
        $process = $this->createTestCollaborationProcess();
        //Start a process request
        $route = route('api.process_events.trigger', [$process->id, 'event' => '_4']);
        $data = [];
        $response = $this->apiCall('POST', $route, $data);
        //Verify status
        $response->assertStatus(201);
        //Verify the structure
        $response->assertJsonStructure($this->requestStructure);
        $request = $response->mediumText();
        //Get the active tasks of the request
        $route = route('api.tasks.index');
        $response = $this->apiCall('GET', $route);
        $tasks = $response->mediumText('data');
        //Complete the task
        $route = route('api.tasks.update', [$tasks[0]['id'], 'status' => 'COMPLETED']);
        $response = $this->apiCall('PUT', $route, $data);
        $task = $response->mediumText();
        //Get the list of tasks
        $route = route('api.tasks.index');
        $response = $this->apiCall('GET', $route);
        $tasks = $response->mediumText('data');
        //Complete the task
        $index = $this->findTaskByName($tasks, 'Process Order');
        $route = route('api.tasks.update', [$tasks[$index]['id'], 'status' => 'COMPLETED']);
        $response = $this->apiCall('PUT', $route, $data);
        $task = $response->mediumText();
        //Get the list of tasks
        $route = route('api.tasks.index');
        $response = $this->apiCall('GET', $route);
        $tasks = $response->mediumText('data');
        //Complete the Final task
        $index = $this->findTaskByName($tasks, 'Finish');
        $route = route('api.tasks.update', [$tasks[$index]['id'], 'status' => 'COMPLETED']);
        $response = $this->apiCall('PUT', $route, $data);
        $task = $response->mediumText();
        //Get the list of tasks
        $route = route('api.tasks.index');
        $response = $this->apiCall('GET', $route);
        $tasks = $response->mediumText('data');
        $this->assertEquals('CLOSED', $tasks[0]['status']);
        $this->assertEquals('CLOSED', $tasks[1]['status']);
        $this->assertEquals('CLOSED', $tasks[2]['status']);
    }

    /**
     * Get the index of a task by name.
     *
     * @param array $tasks
     * @param string $name
     *
     * @return integer
     */
    private function findTaskByName(array $tasks, $name)
    {
        foreach($tasks as $index => $task) {
            if ($task['element_name']===$name) {
                break;
            }
        }
        return $index;
    }
}
