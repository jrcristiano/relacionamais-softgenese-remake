<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'provider_name' => $faker->name,
        'provider_address' => $faker->address,
        'provider_cnpj' => rand(10000000000000, 99999999999999)
    ];
});
