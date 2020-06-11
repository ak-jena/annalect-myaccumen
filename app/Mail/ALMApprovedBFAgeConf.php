<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Campaign;

class ALMApprovedBFAgeConf extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;

    /**
     * Create a new message instance.
     *
     */
    public function __construct(Campaign $campaign)
    {
        //
        $this->campaign     = $campaign;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $campaign_name  = $this->campaign->brief->campaign_name;
        $client_name    = $this->campaign->brief->client->name;
        $agency_name    = $this->campaign->brief->client->agency->name;

        return $this
            ->subject($client_name.' Booking Form Approved '.$campaign_name)
            ->view('emails.workflow.bf-approval-conf-age');
    }
}
