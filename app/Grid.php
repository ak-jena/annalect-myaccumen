<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grid extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'file_name','location'];

    /**
     * Get the Campaign that this booking belongs to
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Get the User that uploaded this targeting grid
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
