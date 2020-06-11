<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DspBudget extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['budget', 'io_host_links', 'dds_code', 'io_file_name', 'io_location'];

    /**
     * DSP this budget is for
     */
    public function dsp()
    {
        return $this->belongsTo('App\Dsp', 'dsp_id');
    }

    /**
     * Booking this budget is for
     */
    public function booking()
    {
        return $this->belongsTo('App\BookingDetail', 'booking_id');
    }

    /**
     * Get the User that uploaded the io
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
