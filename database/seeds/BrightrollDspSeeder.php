<?php

use Illuminate\Database\Seeder;

class BrightrollDspSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('dsps')->insert([
            'dsp_name' => 'Brightroll'
        ]);


        $dsp = \App\Dsp::where('dsp_name', 'Brightroll')->first();

        $dsp->products()->attach([\App\Product::DISPLAY, \App\Product::MOBILE, \App\Product::VOD]);
    }
}
