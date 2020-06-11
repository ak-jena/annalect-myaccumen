<?php

namespace App\Console\Commands;

use App\Campaign;
use Illuminate\Support\Facades\Log as SystemLog;
use Illuminate\Console\Command;
use App\Status;
use App\Log;
use App\User;

class SetCampaignLive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:set-live';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Goes through campaigns in IO uploaded stage and sets the status of eligible ones to live';

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
        $eligible_campaigns = Campaign::getLiveEligibleCampaigns();

        $status = Status::where('name', 'Live Campaign')->first();

        foreach ($eligible_campaigns as $campaign){
            $log = new Log();
            $log->status()->associate($status);
            $log->campaign()->associate($campaign);

            $user = User::findorFail(1);
            $log->user()->associate($user);
            $log->save();
        }
        SystemLog::info('Marked '.$eligible_campaigns->count().' campaign(s) live.');
    }
}
