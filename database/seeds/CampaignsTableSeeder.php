<?php

use Illuminate\Database\Seeder;

class CampaignsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campaigns = factory('App\Campaign', 3)
            ->create()
            ->each(function($c) {
                $c->brief()->save(factory('App\Brief')->make());
            });
    }
}
