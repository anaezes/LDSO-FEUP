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
$factory->define(App\Proposal::class, function (Faker $faker) {

    return [
        'description' =>  "vqwertyuiopçlofgtopo".$faker->text(200),
        'duration' => rand(500, 1000),
        'title' => $faker->unique()->title,
        'datecreated' => date(now()),
        'proposal_status' => 'approved',
        'proposal_public' => $faker->boolean(50),
        'bid_public' => $faker->boolean(50),
        'dateapproved' => $faker->date(now()),
        'duedate' => '2019-12-01',
        'announcedate' => '2019-01-01',
        'idproponent' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});