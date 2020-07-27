<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Note;
use Faker\Generator as Faker;

$factory->define(Note::class, function (Faker $faker) {
    return [
        'note_number' => rand(1000, 20000),
        'note_status' => rand(1,3),
        'note_due_date' => $faker->date(),
        'note_receipt_date' => $faker->date(),
        'note_account_receipt_id' => rand(1,5),

        'note_demand_id' => rand(1, 5)
    ];
});
