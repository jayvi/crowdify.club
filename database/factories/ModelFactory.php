<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'username' => $faker->word,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'usertype_id' => 1,
        'is_email' => true
    ];
});

$factory->define(App\Perk::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
        'description' => $faker->paragraph,
        'value' => $faker->numberBetween(100, 10000),
        'user_id' => factory(App\User::class)->create()->id,
        'type'  => 1
    ];
});

