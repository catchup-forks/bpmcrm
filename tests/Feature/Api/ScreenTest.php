<?php

namespace Tests\Feature\Api;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use App\Models\Screen;
use App\Models\User;
use Tests\TestCase;
use Tests\Feature\Shared\RequestHelper;

class ScreenTest extends TestCase
{
    use RequestHelper;

    const API_TEST_SCREEN = '/screens';

    const STRUCTURE = [
        'title',
        'description',
        'config',
    ];

    /**
     * Test verify the parameter required for create screen
     */
    public function testNotCreatedForParameterRequired()
    {
        //Post should have the parameter required
        $url = self::API_TEST_SCREEN;
        $response = $this->apiCall('POST', $url, []);

        //validating the answer is an error
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->mediumText());
    }

    /**
     * Create Form screen successfully
     */
    public function testCreateFormScreen()
    {
        //Post title duplicated
        $faker = Faker::create();
        $url = self::API_TEST_SCREEN;
        $response = $this->apiCall('POST', $url, [
            'title' => 'Title Screen',
            'type' => 'FORM',
            'description' => $faker->sentence(10)
        ]);

        $response->assertStatus(201);
    }
    /**
     * Create Form screen successfully
     */
    public function testCreateDisplayScreen()
    {
        //Post title duplicated
        $faker = Faker::create();
        $url = self::API_TEST_SCREEN;
        $response = $this->apiCall('POST', $url, [
            'title' => 'Title Screen',
            'type' => 'DISPLAY',
            'description' => $faker->sentence(10)
        ]);

        $response->assertStatus(201);
    }


    /**
     * Can not create a screen with an existing title
     */
    public function testNotCreateScreenWithTitleExists()
    {
        factory(Screen::class)->create([
            'title' => 'Title Screen',
        ]);

        //Post title duplicated
        $faker = Faker::create();
        $url = self::API_TEST_SCREEN;
        $response = $this->apiCall('POST', $url, [
            'title' => 'Title Screen',
            'description' => $faker->sentence(10)
        ]);
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->mediumText());
    }

    /**
     * Get a list of Screen in process without query parameters.
     */
    public function testListScreen()
    {
        Screen::query()->delete();
        //add Screen to process
        $faker = Faker::create();
        factory(Screen::class, 10)->create();

        //List Screen
        $url = self::API_TEST_SCREEN;
        $response = $this->apiCall('GET', $url);
        //Validate the answer is correct
        $response->assertStatus(200);
        //verify count of data
        $json = $response->mediumText();
        $this->assertEquals(10, $json['meta']['total']);

        //verify structure paginate
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);
        $this->assertArrayNotHasKey('links', $json);

        //Verify the structure
        $response->assertJsonStructure(['*' => self::STRUCTURE], $json['data']);
    }

    /**
     * Test to verify that the list dates are in the correct format (yyyy-mm-dd H:i+GMT)
     */
    public function testScreenListDates()
    {
        $newEntity = factory(Screen::class)->create();
        $route = self::API_TEST_SCREEN;
        $response = $this->apiCall('GET', $route);

        $fieldsToValidate = collect(['created_at', 'updated_at']);
        $fieldsToValidate->map(function ($field) use ($response, $newEntity){
            $this->assertEquals(Carbon::parse($newEntity->$field)->format('c'),
                $response->getData()->data[0]->$field);
        });
    }

    /**
     * Get a list of Screen with parameters
     */
    public function testListScreenWithQueryParameter()
    {
        $title = 'search Title Screen';
        factory(Screen::class)->create([
            'title' => $title,
        ]);

        //List Screen with filter option
        $perPage = Faker::create()->randomDigitNotNull;
        $query = '?page=1&per_page=' . $perPage . '&order_by=description&order_direction=DESC&filter=' . urlencode($title);
        $url = self::API_TEST_SCREEN . $query;
        $response = $this->apiCall('GET', $url);
        //Validate the answer is correct
        $response->assertStatus(200);
        //verify structure paginate
        $response->assertJsonStructure([
            'data',
            'meta',
        ]);

        $json = $response->mediumText();

        //verify response in meta
        $this->assertEquals(1, $json['meta']['total']);
        $this->assertEquals($perPage, $json['meta']['per_page']);
        $this->assertEquals(1, $json['meta']['current_page']);

        $this->assertEquals($title, $json['meta']['filter']);
        $this->assertEquals('description', $json['meta']['sort_by']);
        $this->assertEquals('DESC', $json['meta']['sort_order']);
        //verify structure of model
        $response->assertJsonStructure(['*' => self::STRUCTURE], $json['data']);
    }

    /**
     * Get a Screen of a process.
     */
    public function testGetScreen()
    {
        //load Screen
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create([
                'config' => [
                    'field' => 'field 1',
                    'field 2' => [
                        'data1' => 'text',
                        'data2' => 'text 2'
                    ]
                ]
            ])->id;
        $response = $this->apiCall('GET', $url);
        //Validate the answer is correct
        $response->assertStatus(200);

        //verify structure paginate
        $response->assertJsonStructure(self::STRUCTURE);
    }

    /**
     * Update Screen parameter are required
     */
    public function testUpdateScreenParametersRequired()
    {
        //Post should have the parameter title
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create()->id;
        $response = $this->apiCall('PUT', $url, [
            'title' => '',
            'description' => ''
        ]);
        //Validate the answer is incorrect
        $response->assertStatus(422);
        $this->assertArrayHasKey('message', $response->mediumText());
    }

    /**
     * Update Screen in process successfully
     */
    public function testUpdateScreen()
    {
        //Post saved success
        $faker = Faker::create();
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create()->id;
        $response = $this->apiCall('PUT', $url, [
            'title' => 'ScreenTitleTest',
            'description' => $faker->sentence(5),
            'config' => '',
        ]);
        //Validate the answer is correct
        $response->assertStatus(204);
    }
    /**
     * Update Screen Type
     */
    public function testUpdateScreenType()
    {
        $faker = Faker::create();
        $type = 'FORM';
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create([
            'type' => $type
        ])->id;
        $response = $this->apiCall('PUT', $url, [
            'title' => 'ScreenTitleTest',
            'type' => 'DETAIL',
            'description' => $faker->sentence(5),
            'config' => '',
        ]);
        $response->assertStatus(204);
    }

    /**
     * Update Screen with same title
     */
    public function testUpdateSameTitleScreen()
    {
        //Post saved success
        $faker = Faker::create();
        $title = 'Some title';
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create([
            'title' => $title,
        ])->id;
        $response = $this->apiCall('PUT', $url, [
            'title' => $title,
            'description' => $faker->sentence(5),
            'config' => '',
        ]);
        //Validate the answer is correct
        $response->assertStatus(204);
    }

    /**
     * Delete Screen in process
     */
    public function testDeleteScreen()
    {
        //Remove Screen
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->create()->id;
        $response = $this->apiCall('DELETE', $url);
        //Validate the answer is correct
        $response->assertStatus(204);
    }

    /**
     * Delete Screen in process
     */
    public function testDeleteScreenNotExist()
    {
        //screen not exist
        $url = self::API_TEST_SCREEN . '/' . factory(Screen::class)->make()->id;
        $response = $this->apiCall('DELETE', $url);
        //Validate the answer is correct
        $response->assertStatus(405);
    }

}
