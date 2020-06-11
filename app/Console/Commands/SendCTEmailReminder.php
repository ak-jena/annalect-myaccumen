<?php

namespace App\Console\Commands;

use App\Brief;
use App\Mail\CTReminder;
use App\Mail\IOReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendCTEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:ct-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email reminder 3 days before campaign live date to agency users informing them to upload Creative tags';

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
        $outstanding_ct_briefs = Brief::getOutstandingCTBriefs();

        foreach ($outstanding_ct_briefs as $campaign){
            $agency_email_group = $campaign->brief->agencyEmailGroup;

            $recipients = $campaign->brief->getAgencyUsers();

            $email = new CTReminder($campaign);

            Mail::to($recipients)
                ->cc($agency_email_group)
                ->send($email);

        }

    }
}
