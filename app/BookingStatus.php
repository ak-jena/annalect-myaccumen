<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    // correspond to IDs in the DB table
    const DRAFT = 1;
    const SUBMITTED = 2;

    //
    /**
     * Get bookings with this status
     */
    public function bookings()
    {
        return $this->hasMany('App\BookingDetail');
    }
}
