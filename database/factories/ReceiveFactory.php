<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Receive;
use Faker\Generator as Faker;

$factory->define(Receive::class, function (Faker $faker) {
    return [
        'receive_award_real_value' => rand(10000, 100000),
        'receive_taxable_real_value' => rand(10000, 100000),
        'receive_date_receipt' => $faker->date(),
        'receive_status' => rand(1,2),
        'receive_demand_id' => rand(1,2)
    ];
});
