<?php

use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $newBriefStatuses = [
            new \App\Status(['name' => 'New Brief in Progress', 'description' => 'When a brief has been created, action required by agency user', 'button_text' => 'Submit Brief', 'next_status_id' => 2])
        ];

        $targetingGridStatuses = [
            new \App\Status(['name' => 'Brief Submitted', 'description' => 'A brief has been submitted, action required by activation user', 'button_text' => 'Upload Targeting Grid', 'next_status_id' => 3]),
            new \App\Status(['name' => 'Targeting Grid Uploaded', 'description' => 'Grid uploaded, approval required by activation line manager user', 'button_text' => 'Approve Targeting Grid (Line Manager)', 'next_status_id' => 4]),
            new \App\Status(['name' => 'TG Approved by Line Manager', 'description' => 'Approved by line manager, approval required by head of activation if budget >100k', 'button_text' => 'Approve Targeting Grid (Head of Activation)', 'next_status_id' => 5]),
            new \App\Status(['name' => 'TG Approved by Head of Activation', 'description' => 'Approved by head of activation, approval required by agency user', 'button_text' => 'Approve Targeting Grid (Agency User)', 'next_status_id' => 6]),
            new \App\Status(['name' => 'TG Rejected by Line Manager', 'description' => 'Grid rejected by line manager, re-upload required from activation user', 'button_text' => null, 'next_status_id' => null]),
            new \App\Status(['name' => 'TG Rejected by Head of Activation', 'description' => 'Grid rejected by head of activation, re-upload required from activation user', 'button_text' => null, 'next_status_id' => null]),
            new \App\Status(['name' => 'TG Rejected by Agency User', 'description' => 'Grid rejected by agency user, re-upload required from activation user', 'button_text' => null, 'next_status_id' => null])

        ];

        $bookingFormStatuses = [
            new \App\Status(['name' => 'Targeting Grid Approved', 'description' => 'Approved by agency user, booking form required from agency user', 'button_text' => 'Submit Booking Form', 'next_status_id' => 7]),
            new \App\Status(['name' => 'Booking Form Submitted', 'description' => 'Booking form submitted, approval required by activation user', 'button_text' => 'Approve Booking Form (Act. Team)', 'next_status_id' => 8]),
            new \App\Status(['name' => 'BF Approved by Act. Team', 'description' => 'Approved by activation team, approval required by activation line manager user', 'button_text' => 'Approve Booking Form (Act. Line Manager)', 'next_status_id' => 9]),
            new \App\Status(['name' => 'BF Rejected by Act. Team', 'description' => 'Booking form rejected by activation team, action required by agency user', 'button_text' => null, 'next_status_id' => null]),
            new \App\Status(['name' => 'BF Rejected by Act. Line Manager', 'description' => 'Booking form rejected by activation line manager, action required by agency user', 'button_text' => null, 'next_status_id' => null])
        ];

        $ioStatuses = [
            new \App\Status(['name' => 'BF Approved by Act. Line Manager', 'description' => 'Approved by activation line manager user, IO required from agency user', 'button_text' => 'Upload IO', 'next_status_id' => 10]),
            new \App\Status(['name' => 'Host Links added by Agency User', 'description' => 'Host links added by agency user, IO and DDS code required from activation user', 'button_text' => null, 'next_status_id' => null])
        ];

        $creativeTagsStatuses = [
            new \App\Status(['name' => 'IO Uploaded', 'description' => 'IO uploaded, creative tags required from agency user', 'button_text' => 'Upload Creative Tags', 'next_status_id' => 11]),
            new \App\Status(['name' => 'Uploaded Creative Tags', 'description' => 'Uploaded creative tags', 'button_text' => null, 'next_status_id' => null])

        ];

        $sectionsData = array();
        $sectionsData['New Brief'] = array('level' => 1, 'statuses' => $newBriefStatuses);
        $sectionsData['Targeting Grid'] = array('level' => 2, 'statuses' => $targetingGridStatuses);
        $sectionsData['Booking Form'] = array('level' => 3, 'statuses' => $bookingFormStatuses);
        $sectionsData['IO'] = array('level' => 4, 'statuses' => $ioStatuses);
        $sectionsData['Creative Tags'] = array('level' => 5, 'statuses' => $creativeTagsStatuses);

        foreach ($sectionsData as $sectionName => $sectionData){
            DB::table('sections')->insert([
                'name'  => $sectionName,
                'level' => $sectionData['level']
            ]);

            $section = \App\Section::where('name', $sectionName)->first();

            $section->statuses()->saveMany($sectionData['statuses']);

        }
    }
}
