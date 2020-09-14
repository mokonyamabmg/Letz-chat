<?php

use Faker\Generator as Faker;

$factory->define(App\Blogpost::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(10),
        'content' => $faker->paragraph(5, true),

    ];
});
