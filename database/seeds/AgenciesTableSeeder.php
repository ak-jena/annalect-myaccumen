<?php

use Illuminate\Database\Seeder;

class AgenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $agenciesData = array(
            'Hearts and Science',
            'OMD',
            'Rocket',
            'RAPP',
            'Good Stuff',
            'MG OMD',
            'PHD London',
            'PHD North',
            'I2C (External)',
            'Accuen',
            'OMG Programmatic'
        );

        foreach ($agenciesData as $i => $agencyName){
            $agency = factory(App\Agency::class)->create(['name' => $i, 'name' => $agencyName]);

            // create 3 clients for the agency
//            for($k = 0; $k < 3; $k++){
//                factory(App\Client::class)->create(['agency_id' => $agency->id ]);
//            }
        }

    }
}
