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

$a = 0;

$factory->define(App\Team::class, function (Faker $faker) {

    global $a;
    $a++;
    return [
        'id' => $a, //$faker->unique()->randomNumber(),
        'teamname' => $faker->text(20),
        'teamdescription' => $faker->text(200),
        'idleader' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});