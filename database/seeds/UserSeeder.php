<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;

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
        $group_id = factory(Group::class)->create([
            'name' => 'Users',
            'status' => 'ACTIVE'
        ])->id;

        //Create admin user
        $user = factory(User::class)->create([
            'username' => 'admin',
            'password' => 'admin',
            'firstname' => 'admin',
            'lastname' => 'admin',
            'timezone' => null,
            'datetime_format' => null,
            'status' => 'ACTIVE',
            'is_administrator' => true,
        ]);
        

        factory(GroupMember::class)->create([
          'member_id' => $user->id,
          'member_type' => User::class,
          'group_id' => $group_id,
        ]);
    }
}
