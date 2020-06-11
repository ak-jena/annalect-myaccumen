<?php

use Illuminate\Database\Seeder;

class BookingStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('booking_statuses')->insert([
            'name' => 'Draft',
            'description' => 'Booking form has not been completed.'
        ]);

        DB::table('booking_statuses')->insert([
            'name' => 'Submitted',
            'description' => 'Booking form has been submitted.'
        ]);
    }
}
