<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Buzz;
use App\User;
use Faker\Generator as Faker;

$factory->define(Buzz::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomElement(User::all()),
        'body' => $faker->realText(),
        'created_at' => $faker->date(),
        'updated_at' => $faker->date()
    ];
});
