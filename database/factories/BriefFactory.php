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

$factory->define(App\Brief::class, function (Faker\Generator $faker) {
    return [
        'campaign_name' => $faker->name,
        'start_date' => $faker->date(),
        'end_date' => $faker->date(),
        'flighting_considerations' => $faker->paragraph()

    ];
});