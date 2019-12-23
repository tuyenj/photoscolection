<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Photo;
use App\User;
use Faker\Generator as Faker;

$factory->define(Photo::class, function (Faker $faker) {
    return [
        'filename' => $faker->name,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        }
    ];
});
