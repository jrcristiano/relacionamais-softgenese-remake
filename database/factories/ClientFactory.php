<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Client;
use Faker\Generator as Faker;

$factory->define(Client::class, function (Faker $faker) {
    $randomManagerId = \App\Manager::all()->pluck('id')->random();
    return [
        'client_company' => $faker->firstName(),
        'client_address' => $faker->address,
        'client_phone' => $faker->tollFreePhoneNumber,
        'client_responsable_name' => $faker->name,
        'client_cnpj' => rand(10000000000000, 99999999999999),
        'client_manager' => $randomManagerId,
        'client_rate_admin' => rand(1, 10),
        'client_comission_manager' => rand(1, 10),
        'client_state_reg' => $faker->tollFreePhoneNumber,
    ];
});
