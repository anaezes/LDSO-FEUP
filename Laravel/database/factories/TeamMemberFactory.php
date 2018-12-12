<?php

use Faker\Generator as Faker;
use database\factories;

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
$factory->define(App\TeamMember::class, function (Faker $faker) {

    return [
        'idteam' => function () {
            return factory(App\Team::class)->create()->id;
        },
        'iduser' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});