<?php

use Faker\Generator as Faker;

$factory->define(\BrianFaust\Categories\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
