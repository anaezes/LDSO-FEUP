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

$factory->define(App\Proposal::class, function (Faker $faker) {

    return [
                'description' => $faker->text(200),
                'duration' => $faker->numberBetween(300, 1209600),
                'title' => $faker->title,
                'datecreated' => $faker->date(now()),
                'proposal_status' => 'approved',
                'proposal_public' => $faker->boolean(50),
                'bid_public' => $faker->boolean(50),
                'dateapproved' => $faker->date(now()),
                'duedate' => $faker->date('2019-12-01'),
                'announcedate' => $faker->date('2019-01-01')
     ];
});
