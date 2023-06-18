<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create default All Users group
        $group_id = Group::factory()->create([
            'name' => 'Users',
            'status' => 'ACTIVE'
        ])->id;

        //Create admin user

        $user = User::factory()->create([
            'username' => 'admin',
            'password' => 'admin',
            'firstname' => 'admin',
            'lastname' => 'admin',
            'timezone' => null,
            'datetime_format' => null,
            'status' => 'ACTIVE',
            'is_administrator' => true,
        ]);

        GroupMember::factory()->create([
          'member_id' => $user->id,
          'member_type' => User::class,
          'group_id' => $group_id,
        ]);
    }
}
