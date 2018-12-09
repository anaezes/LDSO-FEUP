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
$b = 0;

$factory->define(App\Proposal::class, function (Faker $faker) {

    global $b;
    $b++;
    return [
        'id' => $b, //$faker->unique()->randomNumber(),
        'description' => $faker->text(200),
        'duration' => rand(500, 1209600),
        'title' => $faker->title,
        'proposal_status' => 'approved',
        'proposal_public' => $faker->boolean(50),
        'bid_public' => $faker->boolean(50),
        'dateapproved' => $faker->date(now()),
        'duedate' => $faker->date('2019-12-01'),
        'announcedate' => $faker->date('2019-01-01'),
        'idproponent' => function () {
            return factory(App\User::class)->create()->id;
        }
    ];
});