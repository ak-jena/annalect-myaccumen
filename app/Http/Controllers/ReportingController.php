<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;

class ReportingController extends Controller
{
    public function index()
    {
        $report     = Report::find(1);

        return view('reporting.index', ['existing_report_schedule' => $report]);
    }

    /**
     * Update/insert a report schedule
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'frequency' => 'required',
            'recipients' => 'required',
        ]);

        $report = Report::updateOrCreate(
            ['id' => 1],
            ['frequency'            => $request->frequency,
                'recipients'         => $request->recipients,
                'brief_start_date'  => null,
                'brief_end_date'    => null
            ]
        );

        // run command to export report
        \Artisan::call('report:export', ['criteria' => ['scheduled']]);

        return \Redirect::back()->with('success', 'Reporting schedule successfully updated!');
    }

    /**
     * One off report export for given date range
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $this->validate($request, [
            'recipients' => 'required',
            'campaign_dates' => 'required',
        ]);

        $campaign_dates     = explode(' - ', $request->campaign_dates);
        $start_date         = $campaign_dates[0];
        $end_date           = $campaign_dates[1];

        $recipients         = $request->recipients;

        $one_off_data = ['one off', $recipients, $start_date, $end_date];

        // run command to export report
        \Artisan::call('report:export', [
            'criteria'       => $one_off_data
        ]);


        return \Redirect::back()->with('success', 'Report successfully exported!');
    }
}