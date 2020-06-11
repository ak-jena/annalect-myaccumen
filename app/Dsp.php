<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dsp extends Model
{
    // correspond to IDs in the DB table
    const TUBE_MOGUL    = 1;
    const AOL           = 2;
    const DBM_TV        = 3;
    const AMAZON        = 4;
    const TRADEDESK     = 5;
    const VIDEOLOGY     = 6;
    const ADSWHIZZ      = 7;
    const APPNEXUS      = 8;
    const DBM           = 9;
    const STRIKEAD      = 10;
    const ADELPHIC      = 11;
    const BRIGHTROLL    = 12;

    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['dsp_name'];

    /**
     * DSPs belong to products. They can be shared between products, hence many to many
     */
    public function products()
    {
        return $this->belongsToMany('App\Product', 'dsps_products');
    }
    
}
