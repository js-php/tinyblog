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

$factory->define(\App\Models\Post::class, function (Faker $faker) {
    return [
        'body'          => $faker->paragraph,
        'happened_at'   => $faker->date('Y-m-d')
    ];
});

// $factory->define(App\Tag::class, function (Faker $faker) {
//     return [
//         'name'          => $faker->sentence,
//     ];
// });


