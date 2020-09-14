<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});


$factory->state(App\User::class, 'Bestyn', function (Faker $faker) {
    return [
        'name' => 'Bestyn Mokonyama',
        'email' => 'mokonyamabmg@gmail.com',
        'password' => '$2y$10$QlIQ25cGfuRFB9hqCTTZv.nMqHk/kGb5QLCzkDJ3uZ1m2.hdrgdNi'
    ];
});
