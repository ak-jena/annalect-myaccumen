<?php

namespace App\Console\Commands;

use App\Campaign;
use App\Status;
use Illuminate\Console\Command;
use DB;
use App\Log;
use Illuminate\Support\Facades\Log as SystemLog;
use App\User;

class AutoCloseCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:auto-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command cancels all campaigns whose end date is >= 2 months in the past and have no IO uploaded.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        // get all campaigns who:
        // i) do not have IO
        // ii) are active
        // iii) =>2 months have passed since end date
        $eligible_campaigns = DB::select('SELECT campaigns.id as campaign_id
            FROM campaigns 
            JOIN (
                SELECT MAX(id) log_id, campaign_id
                    FROM logs
                    GROUP BY logs.campaign_id
                 )  c_max ON (c_max.campaign_id = campaigns.id)
            JOIN logs ON (logs.id = c_max.log_id)
            JOIN statuses ON (statuses.id = logs.status_id)
            JOIN briefs ON (briefs.campaign_id = campaigns.id)
            WHERE TIMESTAMPDIFF(MONTH, NOW(), briefs.end_date) <= -2
                AND campaigns.is_active = 1
                AND statuses.id IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14)');

//        dd(count($eligible_campaigns));

        // go through each campaign, set to inactive, and set status to cancelled
        $status = Status::find(Status::CAMPAIGN_CANCELLED);
        foreach ($eligible_campaigns as $campaign_details){

            SystemLog::info('Processing campaign #'.$campaign_details->campaign_id);
            $this->info('Processing campaign #'.$campaign_details->campaign_id);


            $campaign = Campaign::find($campaign_details->campaign_id);
            $campaign->is_active = 0;
            $campaign->save();

            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(1);
            $log->user()->associate($user);
            $log->save();
        }

        SystemLog::info('Cancelled '.count($eligible_campaigns).' campaigns successfully.');
        $this->info('Cancelled '.count($eligible_campaigns).' campaigns successfully.');

    }
}
