<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = array(
            'Developer',
            'Agency User',
            'Activation User',
            'Activation Line Manager',
            'Head of Activation'
        );

        foreach ($roles as $role){
            //
            DB::table('roles')->insert([
                'name'              => $role
            ]);
        }

    }
}
