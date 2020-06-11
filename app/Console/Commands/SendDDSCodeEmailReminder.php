<?php

namespace App\Console\Commands;

use App\Brief;
use App\Mail\DDSCodeReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDDSCodeEmailReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:dds-reminder {elapsed_days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email reminder 3 or 5  days after campaign live date to activation users informing them to upload IO file and DDS Code';

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
        $elapsed_days = $this->argument('elapsed_days');

        $outstanding_dds_code_briefs = Brief::getOutstandingDDSCodeBriefs($elapsed_days);

        foreach ($outstanding_dds_code_briefs as $campaign){
            $recipients = $campaign->brief->getActivationUsers();

            $email = new DDSCodeReminder($campaign);

            Mail::to($recipients)->send($email);

        }
    }
}
