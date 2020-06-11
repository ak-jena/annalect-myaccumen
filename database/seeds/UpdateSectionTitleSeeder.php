<?php

use Illuminate\Database\Seeder;

class UpdateSectionTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('sections')
            ->where('id', 5)
            ->update(['name' => 'Creative Tags and Pixels']);

    }
}
