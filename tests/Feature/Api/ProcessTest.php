<?php
namespace Tests\Feature\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Process;
use App\Models\ProcessCategory;
use App\Models\ProcessCollaboration;
use App\Models\ProcessRequest;
use App\Models\User;
use Tests\Feature\Shared\ResourceAssertionsTrait;
use Tests\TestCase;
use Tests\Feature\Shared\RequestHelper;

/**
 * Tests routes related to processes / CRUD related methods
 *
 * @group process_tests
 */
class ProcessTest extends TestCase
{

    use WithFaker;
    use RequestHelper;
    use ResourceAssertionsTrait;

    protected $resource = 'processes';
    protected $structure = [
        'id',
        'process_category_id',
        'user_id',
        'description',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];


    /**
     * Test to verify our processes listing api endpoint works without any filters
     */
    public function testProcessesListing()
    {
        $initialCount = Process::count();
        // Create some processes
        $countProcesses = 20;
        factory(Process::class, $countProcesses)->create();
        //Get a page of processes
        $page = 2;
        $perPage = 10;
        $this->assertCorrectModelListing(
            '?page=' . $page . '&per_page=' . $perPage,
            [
                'total' => $initialCount + $countProcesses,
                'count' => $perPage,
                'per_page' => $perPage,
                'current_page' => $page,
                'total_pages' => ceil(($initialCount + $countProcesses) / $perPage),
            ]
        );
    }

    /**
     * Test to verify that the list dates are in the correct format (yyyy-mm-dd H:i+GMT)
     */
    public function testProcessListDates()
    {
        $newEntity = factory(Process::class)->create();
        $route = route('api.' . $this->resource . '.index', ['per_page' => 10]);
        $response = $this->apiCall('GET', $route);

        $fieldsToValidate = collect(['created_at', 'updated_at']);
        $fieldsToValidate->map(function ($field) use ($response, $newEntity){
            $this->assertEquals(Carbon::parse($newEntity->$field)->format('c'),
                $response->getData()->data[0]->$field);
        });
    }

    /**
     * Test to verify our processes listing API endpoint works without any filters
     */
    public function testFiltering()
    {
        $perPage = 10;
        $initialActiveCount = Process::where('status','ACTIVE')->count();
        $initialInactiveCount = Process::where('status','INACTIVE')->count();

        // Create some processes
        $processActive = [
            'num' => 10,
            'status' => 'ACTIVE'
        ];
        $processInactive = [
            'num' => 15,
            'status' => 'INACTIVE'
        ];
        factory(Process::class, $processActive['num'])->create(['status' => $processActive['status']]);
        factory(Process::class, $processInactive['num'])->create(['status' => $processInactive['status']]);

        //Get active processes
        $response = $this->assertCorrectModelListing(
            '?filter=ACTIVE&include=category&per_page=' . $perPage,
            [
                'total' => $initialActiveCount + $processActive['num'],
                'count' => $perPage,
                'per_page' => $perPage,
            ]
        );
        //verify include
        $response->assertJsonStructure(['*' => ['category']], $response->mediumText('data'));

        //Get active processes
        $response = $this->assertCorrectModelListing(
            '?filter=INACTIVE&include=category,user&per_page=' . $perPage,
            [
                'total' => $initialInactiveCount + $processInactive['num'],
                'count' => $perPage,
                'per_page' => $perPage,
            ]
        );
        //verify include
        $response->assertJsonStructure(['*' => ['category','user']], $response->mediumText('data'));
    }

    /**
     * Test to verify our processes listing api endpoint works with sorting
     */
    public function testSorting()
    {
        // Create some processes
        factory(Process::class)->create([
            'name' => 'aaaaaa',
            'description' => 'bbbbbb'
        ]);
        factory(Process::class)->create([
            'name' => 'zzzzz',
            'description' => 'yyyyy'
        ]);

        //Test the list sorted by name returns as first row {"name": "aaaaaa"}
        $this->assertModelSorting('?order_by=name&order_direction=asc', [
            'name' => 'aaaaaa'
        ]);

        //Test the list sorted desc returns as first row {"name": "zzzzz"}
        $this->assertModelSorting('?order_by=name&order_direction=DESC', [
            'name' => 'zzzzz'
        ]);

        //Test the list sorted by description in desc returns as first row {"description": "yyyyy"}
        $this->assertModelSorting('?order_by=description&order_direction=desc', [
            'description' => 'yyyyy'
        ]);
    }

    /**
     * Test pagination of process list
     *
     */
    public function testPagination()
    {
        // Number of processes in the tables at the moment of starting the test
        $initialRows = Process::all()->count();

        // Number of rows to be created for the test
        $rowsToAdd = 7;

        // Now we create the specified number of processes
        factory(Process::class, $rowsToAdd)->create();

        // The first page should have 5 items;
        $response = $this->apiCall('GET', route('api.processes.index', ['per_page' => 5, 'page' => 1]));
        $response->assertJsonCount(5, 'data');

        // The second page should have the modulus of 2+$initialRows
        $response = $this->apiCall('GET', route('api.processes.index', ['per_page' => 5, 'page' => 2]));
        $response->assertJsonCount((2 + $initialRows) % 5, 'data');
    }

    /**
     * Test the creation of processes
     */
    public function testProcessCreation()
    {
        //Create a process without category
        $this->assertCorrectModelCreation(
            Process::class, [
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => null,
            ]
        );

        //Create a process without sending the category
        $this->assertCorrectModelCreation(
            Process::class, [
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND,
            ]
        );

        //Create a process with a category
        $category = factory(ProcessCategory::class)->create();
        $this->assertCorrectModelCreation(
            Process::class, [
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => $category->id,
            ]
        );

    }

    /**
     * Test the creation of processes with BPMN definition
     */
    public function testCreateProcessWithBPMN()
    {
        $route = route('api.' . $this->resource . '.store');
        $base = factory(Process::class)->make([
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND,
            ]);
        $array = array_diff($base->toArray(), [static::$DO_NOT_SEND]);
        //Add a bpmn content
        $array['bpmn'] = trim(Process::getProcessTemplate('OnlyStartElement.bpmn'));
        $response = $this->apiCall('POST', $route, $array);
        $response->assertStatus(201);
        $response->assertJsonStructure($this->structure);
        $data = $response->mediumText();
        $process = Process::where('id', $data['id'])->first();
        $this->assertEquals($array['bpmn'], $process->bpmn);
    }

    /**
     * Test the required fields
     */
    public function testCreateProcessFieldsValidation()
    {
        //Test to create a process with an empty name
        $this->assertModelCreationFails(
            Process::class,
            [
                'name' => null,
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND
            ],
            //Fields that should fail
            [
                'name'
            ]
        );

        //Test to create a process with duplicate name
        $name = 'Some name';
        factory(Process::class)->create(['name' => $name]);
        $this->assertModelCreationFails(
            Process::class,
            [
                'name' => $name,
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND
            ],
            //Fields that should fail
            [
                'name'
            ]
        );

        //Test to create a process with a process category id that does not exist
        $this->assertModelCreationFails(
            Process::class,
            [
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => 'id-not-exists'
            ],
            //Fields that should fail
            [
                'process_category_id'
            ]
        );
    }

    /**
     * Test show process
     *
     */
    public function testShowProcess()
    {
        //Create a new process without category
        $process = factory(Process::class)->create([
            'process_category_id' => null
        ]);

        //Test that is correctly displayed
        $this->assertModelShow($process->id, []);

        //Test that is correctly displayed with null category
        $this->assertModelShow($process->id, ['category'])
            ->assertJsonFragment(['category' => null]);

        //Create a new process with category
        $process = factory(Process::class)->create();

        //Test that is correctly displayed including category and user
        $this->assertModelShow($process->id, ['category','user']);
    }

    /**
     * Process deletion
     *
     * Test the process deletion
     */
    public function testsProcessDeletion()
    {
        //Create a new process
        $process = factory(Process::class)->create();

        //Delete the process created
        $this->assertCorrectModelDeletion($process->id);

        //Create a request without collaboration
        $request = factory(ProcessRequest::class)->create([
            'process_collaboration_id' => null
        ]);
        $process = $request->process;

        //Delete the process created
        $this->assertModelDeletionFails($process->id, [
            'requests'
        ]);

        //Create a request with collaboration
        $process = factory(Process::class)->create([
            'process_category_id' => null
        ]);
        factory(ProcessCollaboration::class)->create([
            'process_id' => $process->id
        ]);

        //Delete the process created
        $this->assertModelDeletionFails($process->id, [
            'collaborations'
        ]);
    }

    /**
     * Test update process
     */
    public function testUpdateProcess()
    {
        //Test to update name process
        $name = $this->faker->name;
        $this->assertModelUpdate(
            Process::class,
            [
                'name' => $name,
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND,
                'description' => 'test'
            ]
        );
    }

    /**
     * Test update process
     */
    public function testUpdateProcessWithCategoryNull()
    {
        //Test update process category to null
        $this->assertModelUpdate(
            Process::class,
            [
                'user_id' => static::$DO_NOT_SEND,
                'name' => 'A new name',
                'process_category_id' => null,
                'description' => 'test'
            ]
        );
    }

    /**
     * Test update process
     */
    public function testUpdateProcessWithCategory()
    {
        //Test update process category
        $this->assertModelUpdate(
            Process::class,
            [
                'user_id' => static::$DO_NOT_SEND,
                'name' => 'Another name',
                'process_category_id' => factory(ProcessCategory::class)->create()->id,
                'description' => 'test'
            ]
        );
    }

    /**
     * Test update process with invalid parameters
     */
    public function testUpdateProcessFails()
    {
        //Test to update name and description if required
        $this->assertModelUpdateFails(
            Process::class,
            [
                'name' => '',
                'description' => '',
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND,
            ],
            [
                'name',
                'description'
            ]
        );

        //Test update process category of null
        $this->assertModelUpdateFails(
            Process::class,
            [
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => 'process_category_id_not_exists'
            ],
            [
                'process_category_id'
            ]
        );

        //Test validate name is unique
        $name = 'Some name';
        factory(Process::class)->create(['name' => $name]);
        $this->assertModelUpdateFails(
            Process::class,
            [
                'name' => $name,
                'user_id' => static::$DO_NOT_SEND,
                'process_category_id' => static::$DO_NOT_SEND,
            ],
            [
                'name'
            ]
        );
    }

    /**
     * Test Update BPMN endpoint.
     */
    public function testUpdateBPMN()
    {
        $process = factory(Process::class)->create([
            'bpmn' => Process::getProcessTemplate('OnlyStartElement.bpmn')
        ]);
        $id = $process->id;
        $newBpmn = trim(Process::getProcessTemplate('SingleTask.bpmn'));
        $route = route('api.' . $this->resource . '.update', [$id]);
        $response = $this->apiCall('PUT', $route, [
            'name' => 'test name',
            'description' => 'test description',
            'bpmn' => $newBpmn
        ]);
        //validate status
        $this->assertStatus(200, $response);
        $response->assertJsonStructure($this->structure);
        $updatedProcess = Process::where('id', $id)->first();
        $this->assertEquals($newBpmn, $updatedProcess->bpmn);
    }

    /**
     * Test Update BPMN endpoint with and invalid BPMN content.
     */
    public function testUpdateInvalidBPMN()
    {
        $process = factory(Process::class)->create();
        $id = $process->id;
        $newBpmn = 'Invalid BPMN content';
        $route = route('api.' . $this->resource . '.update', [$id]);
        $response = $this->apiCall('PUT', $route, [
            'bpmn' => $newBpmn
        ]);
        //validate status
        $this->assertStatus(422, $response);
        $response->assertJsonStructure($this->errorStructure);
    }
}
