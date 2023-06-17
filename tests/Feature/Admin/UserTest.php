<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\Feature\Shared\RequestHelper;

class UserTest extends TestCase
{
    use RequestHelper;

    /**
     * Test to make sure the controller and route work with the view
     *
     * @return void
     */
    public function testIndexRoute()
    {

        // get the URL
        $response = $this->webCall('GET', '/admin/users');
        // check the correct view is called
        $response->assertViewIs('admin.users.index');

        $response->assertStatus(200);
        $response->assertSee('Users');

    }

    /**
     * Test to make sure the controller and route work wiht the view
     *
     * @return void
     */
    public function testEditRoute()
    {

        $user_id = factory(User::class)->create()->id;
        // get the URL
        $response = $this->webCall('GET', '/admin/users/' . $user_id . '/edit');

        $response->assertStatus(200);
        // check the correct view is called
        $response->assertViewIs('admin.users.edit');
        $response->assertSee('Edit User');
    }

    /**
     * Test to make sure the controller and route work with the view
     *
     * @return void
     */
    public function testCreateRoute()
    {
        // get the URL
        $response = $this->webCall('GET', '/admin/users/create');

        $response->assertStatus(200);
        // check the correct view is called
        $response->assertViewIs('admin.users.create');

    }

    /**
     * Test to make sure the controller and route work with the view
     *
     * @return void
     */
    public function testShowRoute()
    {
        $user = factory(User::class)->create();
        // get the URL
        $response = $this->webCall('GET', '/admin/users/' . $user->id);
        $response->assertStatus(200);
        // check the correct view is called
        $response->assertViewIs('admin.users.show');
        $response->assertSee($user->firstname);

    }

}
