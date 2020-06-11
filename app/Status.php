<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    // correspond to IDs in the DB table
    const NEW_BRIEF = 1;
    const BRIEF_SUBMITTED = 2;
    const TARGETING_GRID_UPLOADED = 3;
    const TG_APPROVED_BY_LINE_MANAGER = 4;
    const TG_APPROVED_BY_HEAD_OF_ACTIVATION = 5;
    const TG_REJECTED_BY_LINE_MANAGER = 6;
    const TG_REJECTED_BY_HEAD_OF_ACTIVATION = 7;
    const TG_REJECTED_BY_AGENCY_USER = 8;
    const TARGETING_GRID_APPROVED = 9;
    const BOOKING_FORM_SUBMITTED = 10;
    const BF_APPROVED_BY_ACT_TEAM = 11;
    const BF_REJECTED_BY_ACT_TEAM = 12;
    const BF_REJECTED_BY_ACT_LINE_MANAGER = 13;
    const BF_APPROVED_BY_ACT_LINE_MANAGER = 14;
    const ADDED_IO_HOST_LINKS = 15;
    const IO_UPLOADED = 16;
    const UPLOADED_CREATIVE_TAGS = 17;
    const CAMPAIGN_CANCELLED = 18;
    const CAMPAIGN_LIVE = 19;
    const CAMPAIGN_COMPLETED = 20;
    //

    /**
     * Get the section that owns the status.
     */
    public function section()
    {
        return $this->belongsTo('App\Section');
    }

    /**
     * Get the next status in the workflow
     */
    public function nextStatus()
    {
        return $this->hasOne('App\Status', 'next_status_id');
    }

    /**
     * Get logs for this status
     */
    public function logs()
    {
        return $this->hasMany('App\Log');
    }
}
