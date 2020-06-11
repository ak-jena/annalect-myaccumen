<?php

namespace App\Console\Commands;

use App\Campaign;
use Illuminate\Support\Facades\Log as SystemLog;
use Illuminate\Console\Command;
use App\Status;
use App\Log;
use App\User;

class SetCampaignCompleted extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:set-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sets eligible campaigns to completed';

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
        //
        //
        $eligible_campaigns = Campaign::getCompletedEligibleCampaigns();

        $status = Status::where('name', 'Completed Campaign')->first();

        foreach ($eligible_campaigns as $campaign){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(1);
            $log->user()->associate($user);
            $log->save();
        }
        SystemLog::info('Marked '.$eligible_campaigns->count().' campaign(s) complete.');
    }
}
