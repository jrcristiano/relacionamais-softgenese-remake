<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Manager;
use Faker\Generator as Faker;

$factory->define(Manager::class, function (Faker $faker) {
    return [
        'manager_name' => $faker->name,
        'manager_phone' => $faker->tollFreePhoneNumber,
        'manager_email' => $faker->email,
        'manager_cpf' => $faker->tollFreePhoneNumber,
    ];
});
