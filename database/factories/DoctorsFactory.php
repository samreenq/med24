<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Doctor::class, function (Faker $faker) {
    return [
        //
        'first_name'=>$faker->firstName,
        'last_name'=>$faker->lastName,
        'email'=>$faker->email,
        'gender'=>$faker->randomElement(['male','female']),
        'phone'=>$faker->phoneNumber,
        'biography'=>$faker->paragraph,
        'summary'=>$faker->paragraph,
       // 'care_philosophy'=>$faker->parse('Early To Bed Early To Rise , Makes A Man Healthy Wealthy And Wise'),
        'status'=>1,
        'ip_address'=>$faker->ipv4,
        'medical_licence'=>$faker->randomNumber(),
        'image'=>'doctor.png',
        'password'=>bcrypt('12345678'),

    ];
});
