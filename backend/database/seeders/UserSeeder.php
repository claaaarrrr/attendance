<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $User = new User();
        $User->username = 'admin';
        $User->hashed_user_id = hash('sha256', 1);
        $User->password = Hash::make('123');
        $User->first_name = $faker->firstName;
        $User->middle_name = $faker->lastName;
        $User->last_name = $faker->lastName;
        $User->suffix = $faker->suffix;
        $User->user_role = 0; // 0 is admin 1 is endusers
        $User->user_role_desc = 'admin';
        $User->email = $faker->email;
        $User->gender = $faker->randomElement(['male', 'female']);
        $User->profile_pic_path = '/ProfilePic/avatar' . $faker->randomElement(['1', '2', '3', '4']) . '.PNG';
        $User->address = $faker->address;
        $User->save();

        $guidance = new User();
        $guidance->username = 'guidance';
        $guidance->hashed_user_id = hash('sha256', 2);
        $guidance->password = Hash::make('123');
        $guidance->first_name = $faker->firstName;
        $guidance->middle_name = $faker->lastName;
        $guidance->last_name = $faker->lastName;
        $guidance->suffix = $faker->suffix;
        $guidance->user_role = 2;
        $guidance->user_role_desc = 'guidance';
        $guidance->email = $faker->email;
        $guidance->gender = $faker->randomElement(['male', 'female']);
        $guidance->profile_pic_path = '/ProfilePic/avatar' . $faker->randomElement(['1', '2', '3', '4']) . '.PNG';
        $guidance->address = $faker->address;
        $guidance->save();

        $clinic = new User();
        $clinic->username = 'clinic';
        $clinic->hashed_user_id = hash('sha256', 3);
        $clinic->password = Hash::make('123');
        $clinic->first_name = $faker->firstName;
        $clinic->middle_name = $faker->lastName;
        $clinic->last_name = $faker->lastName;
        $clinic->suffix = $faker->suffix;
        $clinic->user_role = 2;
        $clinic->user_role_desc = 'clinic';
        $clinic->email = $faker->email;
        $clinic->gender = $faker->randomElement(['male', 'female']);
        $clinic->profile_pic_path = '/ProfilePic/avatar' . $faker->randomElement(['1', '2', '3', '4']) . '.PNG';
        $clinic->address = $faker->address;
        $clinic->save();

        $headsoffice = new User();
        $headsoffice->username = 'headsoffice';
        $headsoffice->hashed_user_id = hash('sha256', 4);
        $headsoffice->password = Hash::make('123');
        $headsoffice->first_name = $faker->firstName;
        $headsoffice->middle_name = $faker->lastName;
        $headsoffice->last_name = $faker->lastName;
        $headsoffice->suffix = $faker->suffix;
        $headsoffice->user_role = 2;
        $headsoffice->user_role_desc = 'headsoffice';
        $headsoffice->email = $faker->email;
        $headsoffice->gender = $faker->randomElement(['male', 'female']);
        $headsoffice->profile_pic_path = '/ProfilePic/avatar' . $faker->randomElement(['1', '2', '3', '4']) . '.PNG';
        $headsoffice->address = $faker->address;
        $headsoffice->save();

        User::factory(9)->create();
    }
}
