<?php

namespace Tests\Feature\Api;

use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use Tests\TestCase;
use Tests\Feature\Shared\RequestHelper;
use Illuminate\Support\Facades\Hash;

class GroupMembersTest extends TestCase
{

  use RequestHelper;

  const API_TEST_URL = '/group_members';

  const STRUCTURE = [
      'id',
      'group_id',
      'member_id',
      'member_type',
      'updated_at',
      'created_at'
  ];

  /**
   * List group memberships
   */

   public function testGetGroupMemberList()
   {
     $response = $this->apiCall('GET', self::API_TEST_URL);
     $response->assertStatus(200);

     $groupmembership = factory(GroupMember::class)->create();

     $response = $this->apiCall('GET', self::API_TEST_URL.'/?filter='.$groupmembership->member_id);
     $response->assertStatus(200);

   }

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
   * Create new group successfully
   */
  public function testCreateGroupMembershipForUser()
  {
      $user = factory(User::class)->create();
      $group = factory(Group::class)->create();

      $response = $this->apiCall('POST', self::API_TEST_URL, [
          'group_id' => $group->id,
          'member_id' => $user->id,
          'member_type' => User::class,
      ]);

      //Validate the header status code
      $response->assertStatus(201);

      // make sure it saved the relationship
      $related_group = $user->groupMembersFromMemberable()->first()->group;
      $this->assertTrue($related_group->is($group));

      $member_user = $group->groupMembers()->first()->member;
      $this->assertTrue($member_user->is($user));
  }

  public function testCreateGroupMembershipForGroup()
  {
      $this->withoutExceptionHandling();
      $group1 = factory(Group::class)->create();
      $group2 = factory(Group::class)->create();

      $response = $this->apiCall('POST', self::API_TEST_URL, [
          'group_id' => $group1->id,
          'member_id' => $group2->id,
          'member_type' => Group::class,
      ]);

      //Validate the header status code
      $response->assertStatus(201);

      // make sure it saved the relationship
      $related_group = $group1->groupMembers()->first()->member;
      $this->assertTrue($related_group->is($group2));

      $member_group = $group2->groupMembersFromMemberable()->first()->group;
      $this->assertTrue($member_group->is($group1));
  }

  /**
   * Get a group
   */
  public function testGetGroupMember()
  {
      //get the id from the factory
      $group = factory(GroupMember::class)->create()->id;

      //load api
      $response = $this->apiCall('GET', self::API_TEST_URL. '/' . $group);

      //Validate the status is correct
      $response->assertStatus(200);

      //verify structure
      $response->assertJsonStructure(self::STRUCTURE);
  }

  /**
   * Delete group in process
   */
  public function testDeleteGroupMember()
  {
      //Remove group
      $url = self::API_TEST_URL . '/' . factory(GroupMember::class)->create()->id;
      $response = $this->apiCall('DELETE', $url);

      //Validate the header status code
      $response->assertStatus(204);
  }

  /**
   * The group does not exist in process
   */
  public function testDeleteGroupMemberNotExist()
  {
      //GroupMember not exist
      $url = self::API_TEST_URL . '/' . factory(GroupMember::class)->make()->id;
      $response = $this->apiCall('DELETE', $url);

      //Validate the header status code
      $response->assertStatus(405);
  }

}
