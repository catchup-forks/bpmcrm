<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Group;
use Tests\Feature\Shared\RequestHelper;

class GroupTest extends TestCase
{
    use RequestHelper;

    /**
     * Test to make sure the controller and route work with the view
     *
     * @return void
     */
    public function testIndexRoute()
    {
        $response = $this->webCall('GET', '/admin/groups');
        $response->assertStatus(200);
        $response->assertViewIs('admin.groups.index');
    }

    /**
     * Test to make sure the controller and route work with the view EDIT
     *
     * @return void
     */
    public function testEditRoute()
    {
        $groupId = factory(Group::class)->create()->getKey();
        // get the URL
        $response = $this->webCall('GET', '/admin/groups/' . $groupId . '/edit');

        $response->assertStatus(200);
        // check the correct view is called
        $response->assertViewIs('admin.groups.edit');
        $response->assertSee('Edit Group');
    }

    /**
     * Test to make sure the controller and route work with the view SHOW
     *
     * @return void
     */
    public function testShowRoute()
    {
        $group = factory(Group::class)->create();
        // get the URL
        $response = $this->webCall('GET', '/admin/groups/' . $group->id);
        $response->assertStatus(200);
        // check the correct view is called
        $response->assertViewIs('admin.groups.show');
        $response->assertSee($group->name);
    }
}
