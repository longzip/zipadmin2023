<?php

use Faker\Generator as Faker;

$factory->define(\NoiThatZip\Contact\Models\Contact::class, function (Faker $faker) {
    return [
        'last_name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'address' => $faker->address,
        'start_date' => $faker->dateTime($max = 'now')
    ];
});
