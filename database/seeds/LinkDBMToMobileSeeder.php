<?php

use Illuminate\Database\Seeder;

class LinkDBMToMobileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $dbm    = \App\Dsp::find(\App\Dsp::DBM);
        $mobile = \App\Product::find(\App\Product::MOBILE);

        $dbm->products()->attach($mobile->id);

    }
}
