<?php

use Illuminate\Database\Seeder;

class DspsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $dsps_data = array(
            'Tube Mogul' => array(5),
            'AOL' => array(5),
            'DBM (Trueview)' => array(5),
            'Amazon' => array(1, 5),
            'The Tradedesk' => array(1, 2, 3, 5),
            'Videology' => array(5),
            'Adwizz' => array(4),
            'Appnexus'=> array(1, 2, 3),
            'DBM' => array(1, 2),
            'Strikead' => array(3),
            'Adelphic' => array(3)
        );

        foreach ($dsps_data as $dsp_name => $dsp_products){
            DB::table('dsps')->insert([
                 'dsp_name' => $dsp_name
            ]);

            $dsp = \App\Dsp::where('dsp_name', $dsp_name)->first();

            $dsp->products()->sync($dsp_products);
        }
    }
}
