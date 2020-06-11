<?php

use Illuminate\Database\Seeder;

class CancelledCompletedStatusesSeeder extends Seeder
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
            'name' => 'Live Campaign',
            'description' => 'Campaign has commenced.',
            'button_text' => null,
            'next_status_id' => null,
            'section_id' => null
        ]);

        DB::table('statuses')->insert([
            'name' => 'Completed Campaign',
            'description' => 'Campaign has finished.',
            'button_text' => null,
            'next_status_id' => null,
            'section_id' => null
        ]);
    }
}
