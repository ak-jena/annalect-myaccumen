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
        'name' => $faker->name,
        'username' => str_replace(' ','.',strtolower($faker->name)),
        'email' => $faker->email,
        'password' => bcrypt('Media124'),
        'remember_token' => str_random(10),
        'last_login'        => null,
        'blocked'           => false,
        'pagination'        => 25,
        'num_cutoff'        => 2,
        'site_skin'         => 'skin-purple',
        'menubar_collapse'  => false,
        'can_viewas'        => true,
        'can_manage_user'   => true,
        'role_id'           => 1
    ];
});