<?php

namespace App\Console;

use App\Console\Commands\SendCTEmailReminder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\SendIOEmailReminder::class,
        \App\Console\Commands\SendCTEmailReminder::class,
        \App\Console\Commands\SendDDSCodeEmailReminder::class,
        \App\Console\Commands\SetCampaignLive::class,
        \App\Console\Commands\SetCampaignCompleted::class,
        \App\Console\Commands\ExportReport::class,
        \App\Console\Commands\AutoCloseCampaigns::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('email:io-reminder')
            ->dailyAt('09:30');

        $schedule->command('email:ct-reminder')
            ->dailyAt('09:30');

        $schedule->command('email:dds-reminder 3')
            ->dailyAt('09:30');

        $schedule->command('email:dds-reminder 5')
            ->dailyAt('09:30');

        $schedule->command('campaigns:set-complete')
            ->dailyAt('00:01');

        $schedule->command('campaigns:set-live')
            ->dailyAt('00:01');

        // run report and export to email recipients

        $report_schedule = \App\Report::find(1);

        if($report_schedule !== null){
            $frequency = $report_schedule->frequency;

            if($frequency == 'weekly'){
                $schedule->command('report:export', ['scheduled'])->weekly()->mondays()->at('09:30');
            }elseif ($frequency == 'daily'){
                $schedule->command('report:export', ['scheduled'])->dailyAt('09:30');
            }elseif ($frequency == 'monthly'){
                $schedule->command('report:export', ['scheduled'])->monthlyOn(1, '09:30');
            }
        }

    }
}
