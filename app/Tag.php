<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'user_id', 'file_name','location', 'fileshare_links', 'file_type'];

    /**
     * Get the Campaign that this creative tag belongs to
     */
    public function campaign()
    {
        return $this->belongsTo('App\Campaign');
    }

    /**
     * Get the User that uploaded this creative tag
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
