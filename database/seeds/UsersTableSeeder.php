<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $usersData = array(
            'Saeed Bhuta' => array('email' => 'saeed.bhuta@annalect.com', 'role_id' => 1),
            'Amrit Gurung' => array('email' => 'amrit.gurung@annalect.com', 'role_id' => 1),
            'Mohammed Kaykobad' => array('email' => 'mohammed.kaykobad@annalect.com', 'role_id' => 1),
            'Richard Shires' => array('email' => 'richard.shires@annalect.com', 'role_id' => 1),
            'Ray Steele' => array('email' => 'ray.steele@annalect.com', 'role_id' => 1),
            'Saarika Nathwani' => array('email' => 'saarika.nathwani@omnicommediagroup.com', 'role_id' => 1)
        );

        foreach ($usersData as $i => $userData){
            $username = str_replace(' ','.',strtolower($i));
            $role_id = $userData['role_id'];

            factory(App\User::class)->create(['name' => $i, 'username' => $username, 'email' => $userData['email'], 'role_id' => $role_id ]);
        }

    }
}
