<?php

use Illuminate\Database\Seeder;

class UpdateDbmDspNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dsps')
            ->where('id', 3)
            ->update(['dsp_name' => 'DBM Budget']);
    }
}
