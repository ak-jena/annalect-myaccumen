<?php

namespace App\Mail;

use App\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BFUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $campaign;

    /**
     * Create a new message instance.
     *
     * @return void
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

        return $this
            ->subject($client_name.' Booking Form Updated '.$campaign_name)
            ->view('emails.workflow.booking-updated');
    }
}
