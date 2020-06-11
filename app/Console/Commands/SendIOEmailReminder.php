<?php

namespace App\Console\Commands;

use App\Brief;
use App\Mail\IOReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendIOEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:io-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email reminder 5 days before campaign live date to agency users informing them to upload io links';

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
        $outstanding_io_briefs = Brief::getOutstandingIoBriefs();

        foreach ($outstanding_io_briefs as $campaign){
            $recipients = $campaign->brief->getAgencyUsers();

            $email = new IOReminder($campaign);

            Mail::to($recipients)->send($email);

        }
    }
}
