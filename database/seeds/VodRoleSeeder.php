<?php

use Illuminate\Database\Seeder;

class VodRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            'name' => 'VOD User'
        ]);
    }
}
