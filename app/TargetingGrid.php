<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetingGrid extends Model
{
    //

    /**
     * Get the campaign that owns the grid.
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Get the product the grid is for.
     */
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    /**
     * Get the user that created the grid.
     */
    public function user()
    {
        return $this->belongsTo('App\Grid');
    }
}
