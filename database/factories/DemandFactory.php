<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Demand;
use Faker\Generator as Faker;

$factory->define(Demand::class, function (Faker $faker) {
    return [
        'demand_client_cnpj' => $faker->tollFreePhoneNumber,
        'demand_client_name' => $faker->name,
        'demand_prize_amount' => rand(1000, 10000),
        'demand_taxable_amount' => rand(10000, 100000),
        'demand_nfe_total' => rand(1000, 10000),
    ];
});
