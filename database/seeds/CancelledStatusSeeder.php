<?php

use Illuminate\Database\Seeder;

class CancelledStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('statuses')->insert([
            'name' => 'Campaign cancelled.',
            'description' => 'Campaign has been cancelled.',
            'button_text' => null,
            'next_status_id' => null,
            'section_id' => null
        ]);

    }
}
