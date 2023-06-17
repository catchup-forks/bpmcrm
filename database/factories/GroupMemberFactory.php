<?php

use Faker\Generator as Faker;
use App\Models\Group;
use App\Models\User;
use App\Models\GroupMember;

/**
 * Model factory for a Group
 */
$factory->define(GroupMember::class, function (Faker $faker) {
    return [         
        'member_id' => function () {
            return factory(User::class)->create()->getKey();
        },
        'member_type' => User::class,
        'group_id' => function () {
            return factory(Group::class)->create()->getKey();
        }
    ];
});
