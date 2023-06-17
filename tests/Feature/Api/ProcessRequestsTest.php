<?php
namespace Tests\Feature\Api;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Process;
use App\Models\ProcessRequest;
use Tests\Feature\Shared\RequestHelper;
use Tests\TestCase;

/**
 * Tests routes related to processes / CRUD related methods
 *
 * @group process_tests
 */
class ProcessRequestsTest extends TestCase
{

    use RequestHelper;
    use WithFaker;

    const API_TEST_URL = '/requests';

    const STRUCTURE = [
        'id',
        'process_id',
        'process_collaboration_id',
        'user_id',
        'participant_id',
        'status',
        'name',
        'completed_at',
        'initiated_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Test verify the parameter required for create form
     */
    public function testNotCreatedForParameterRequired()
    {
        //Post should have the parameter required
        $response = $this->apiCall('POST', self::API_TEST_URL, []);

        //Validate the header status code
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->mediumText());
    }

    /**
     * Create new request successfully
     */
    public function testCreateRequest()
    {
        $process = factory(Process::class)->create();

        $response = $this->apiCall('POST', self::API_TEST_URL, [
            'process_id' => $process->id,
            'process_collaboration_id' => null,
            'callable_id' => $this->faker->randomDigit,
            'status' => 'ACTIVE',
            'name' => 'RequestName',
            'data' => '{}'
        ]);

        //Validate the header status code
        $response->assertStatus(201);
    }

    /**
     * Can not create a request with an existing requestname
     */
    public function testNotCreateRequestWithRequestNameExists()
    {
        factory(ProcessRequest::class)->create([
            'name' => 'duplicated name',
        ]);

        $process = factory(Process::class)->create();

        //Post request name duplicated
        $response = $this->apiCall('POST', self::API_TEST_URL, [
            'process_id' => $process->id,
            'process_collaboration_id' => null,
            'status' => 'ACTIVE',
            'name' => 'duplicated name',
            'data' => '{}'
        ]);

        //Validate the header status code
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->mediumText());
    }

    /**
     * Get a list of Requests without query parameters.
     */
    public function testListRequest()
    {
        ProcessRequest::query()->delete();

        factory(ProcessRequest::class, 10)->create();

        $response = $this->apiCall('GET', self::API_TEST_URL);

        //Validate the header status code
        $response->assertStatus(200);

        // Verify structure
        $response->assertJsonStructure([
            'data' => ['*' => self::STRUCTURE],
            'meta',
        ]);

        // Verify count
        $this->assertEquals(10, $response->mediumText()['meta']['total']);
    }

    /**
     * Test to verify that the list dates are in the correct format (yyyy-mm-dd H:i+GMT)
     */
    public function testScreenListDates()
    {
        $newEntity = factory(ProcessRequest::class)->create();
        $route = self::API_TEST_URL;
        $response = $this->apiCall('GET', $route);

        $fieldsToValidate = collect(['created_at', 'updated_at']);
        $fieldsToValidate->map(function ($field) use ($response, $newEntity){
            $this->assertEquals(Carbon::parse($newEntity->$field)->format('c'),
                $response->getData()->data[0]->$field);
        });
    }


    /**
     * Get a list of Request with parameters
     */
    public function testListRequestWithQueryParameter()
    {
        $requestname = 'mytestrequestname';

        factory(ProcessRequest::class)->create([
            'name' => $requestname,
        ]);

        //List Request with filter option
        $perPage = Faker::create()->randomDigitNotNull;
        $query = '?page=1&per_page=' . $perPage . '&order_by=name&order_direction=DESC&filter=' . $requestname;
        $response = $this->apiCall('GET', self::API_TEST_URL . $query);

        //Validate the header status code
        $response->assertStatus(200);

        //verify structure paginate
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);

        // Verify return data
        $this->assertEquals(1, $response->mediumText()['meta']['total']);
        $this->assertEquals('name', $response->mediumText()['meta']['sort_by']);
        $this->assertEquals('DESC', $response->mediumText()['meta']['sort_order']);
    }

    /**
     * Get a list of Request by type 
     */
    public function testListRequestWithType()
    {
        $in_progress = factory(ProcessRequest::class)->create([
            'status' => 'ACTIVE',
        ]);
        
        $completed = factory(ProcessRequest::class)->create([
            'status' => 'COMPLETED',
        ]);
        $response = $this->apiCall('GET', self::API_TEST_URL . '/?type=completed');
        $json = $response->mediumText();
        $this->assertCount(1, $json['data']);
        $this->assertEquals($completed->id, $json['data'][0]['id']);
        
        $response = $this->apiCall('GET', self::API_TEST_URL . '/?type=in_progress');
        $json = $response->mediumText();
        $this->assertCount(1, $json['data']);
        $this->assertEquals($in_progress->id, $json['data'][0]['id']);
    }
    
    /**
     * Get a list of Request with assocations included
     */
    public function testListRequestWithIncludes()
    {
        $process = factory(Process::class)->create();

        factory(ProcessRequest::class)->create([
            'process_id' => $process->id,
        ]);

        $response = $this->apiCall('GET', self::API_TEST_URL . '/?include=process');
        $json = $response->mediumText();
        $this->assertEquals($process->id, $json['data'][0]['process']['id']);
    }

    /**
     * Get a request
     */
    public function testGetRequest()
    {
        //get the id from the factory
        $request = factory(ProcessRequest::class)->create()->id;

        //load api
        $response = $this->apiCall('GET', self::API_TEST_URL. '/' . $request);

        //Validate the status is correct
        $response->assertStatus(200);

        //verify structure
        $response->assertJsonStructure(self::STRUCTURE);
    }

    /**
     * Parameters required for update of request
     */
    public function testUpdateProcessRequestParametersRequired()
    {
        $id = factory(ProcessRequest::class)->create(['name' => 'mytestrequestname'])->id;
        //The post must have the required parameters
        $url = self::API_TEST_URL . '/' .$id;

        $response = $this->apiCall('PUT', $url, [
            'name' => null
        ]);

        //Validate the header status code
        $response->assertStatus(422);
    }


    /**
     * Update request in process
     */
    public function testUpdateProcessRequest()
    {
        $faker = Faker::create();

        $url = self::API_TEST_URL . '/' . factory(ProcessRequest::class)->create()->id;

        //Load the starting request data
        $verify = $this->apiCall('GET', $url);

        //Post saved success
        $response = $this->apiCall('PUT', $url, [
            'name' => $faker->unique()->name,
            'data' => '{"test":1}',
            'process_id' => json_decode($verify->getContent())->process_id
        ]);

        //Validate the header status code
        $response->assertStatus(204);

        //Load the updated request data
        $verify_new = $this->apiCall('GET', $url);

        //Check that it has changed
        $this->assertNotEquals($verify,$verify_new);
    }

    /**
     * Check that the validation wont allow duplicate requestnames
     */
    public function testUpdateProcessRequestTitleExists()
    {
        $request1 = factory(ProcessRequest::class)->create([
            'name' => 'MyRequestName',
        ]);

        $request2 = factory(ProcessRequest::class)->create();

        $url = self::API_TEST_URL . '/' . $request2->id;

        $response = $this->apiCall('PUT', $url, [
            'name' => 'MyRequestName',
        ]);
        //Validate the header status code
        $response->assertStatus(422);
        $response->assertSeeText('The name has already been taken');
    }

    /**
     * Delete request in process
     */
    public function testDeleteProcessRequest()
    {
        //Remove request
        $url = self::API_TEST_URL . '/' . factory(ProcessRequest::class)->create()->id;
        $response = $this->apiCall('DELETE', $url);

        //Validate the header status code
        $response->assertStatus(204);
    }

    /**
     * The request does not exist in process
     */
    public function testDeleteProcessRequestNotExist()
    {
        //ProcessRequest not exist
        $url = self::API_TEST_URL . '/' . factory(ProcessRequest::class)->make()->id;
        $response = $this->apiCall('DELETE', $url);

        //Validate the header status code
        $response->assertStatus(405);
    }
}
