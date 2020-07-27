<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Bill;
use Faker\Generator as Faker;

$factory->define(Bill::class, function (Faker $faker) {
    return [
        'bill_value' => rand(1000, 10000),
        'bill_payday' => $faker->date(),
        'bill_due_date' => $faker->date(),
        'bill_bank_id' => 1,
        'bill_payment_status' => 2,
        'bill_provider_id' => 1,
    ];
});
