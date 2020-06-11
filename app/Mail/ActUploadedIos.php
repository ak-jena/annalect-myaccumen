<?php

namespace App\Mail;

use App\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActUploadedIos extends Mailable
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

        return $this
            ->subject($client_name.'Host Links, IO Files and DDS Codes Submitted '.$campaign_name)
            ->view('emails.workflow.act-uploaded-io-file-code');
    }
}
