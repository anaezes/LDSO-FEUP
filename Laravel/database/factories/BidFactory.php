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
$factory->define(App\Bid::class, function (Faker $faker) {
    return [
        'idproposal' => function () {
            return factory(App\Proposal::class)->create()->id;
        },
        'idteam' => function () {
            return factory(App\Team::class)->create()->id;
        },
        'description' => $faker->text(500),
        'submissiondate' => $faker->date('2019-01-01')
    ];
});