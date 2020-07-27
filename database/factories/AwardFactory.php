<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Award;
use Faker\Generator as Faker;

$factory->define(Award::class, function (Faker $faker) {
    return [
        'awarded_value' => rand(10000, 100000),
        'awarded_type' => rand(1,2),
        'awarded_status' => rand(1,3),
        'award_demand_id' => rand(1, 5)
    ];
});
