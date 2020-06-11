<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    //
    /**
     * Get the status.
     */
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    /**
     * Get the campaign this log is for
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Get the user that actioned this log
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
