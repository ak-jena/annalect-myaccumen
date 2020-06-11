<?php

use Illuminate\Database\Seeder;

class SystemCommentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name'      => 'System Message',
            'username'  => 'system.message',
            'email'     => 'lonomguksysdevteam@omnicommediagroup.com',
            'password' => bcrypt('Media124'),
            'remember_token' => str_random(10),
            'last_login'        => null,
            'blocked'           => false,
            'pagination'        => 25,
            'num_cutoff'        => 2,
            'site_skin'         => 'skin-purple',
            'menubar_collapse'  => false,
            'can_viewas'        => true,
            'can_manage_user'   => true,
            'role_id'           => 1
        ]);
    }
}
