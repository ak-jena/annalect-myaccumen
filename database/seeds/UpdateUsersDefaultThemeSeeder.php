<?php

use Illuminate\Database\Seeder;

class UpdateUsersDefaultThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')
            ->update(['site_skin' => 'skin-green']);
    }
}
