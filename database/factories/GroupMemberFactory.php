<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupMemberFactory extends Factory
{
    protected $model = GroupMember::class;

    /**
     * @return array{member_id: Closure, member_type: class-string<User>, group_id: Closure}
     */
    public function definition(): array
    {
        return [
            'member_id' => function () {
                return User::factory()->create()->getKey();
            },
            'member_type' => User::class,
            'group_id' => function () {
                return Group::factory()->create()->getKey();
            }
        ];
    }
}
