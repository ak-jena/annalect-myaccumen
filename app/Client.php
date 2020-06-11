<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','agency_id','logo', 'model'];


    /**
     * Get the parent agency for this client
     */
    public function agency()
    {
        return $this->belongsTo('App\Agency');
    }

    /**
     * Get the briefs belonging to this client
     */
    public function briefs()
    {
        return $this->hasMany('App\Brief');
    }

    /**
     * The users that assigned to the client.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'clients_users')->withTimestamps();
    }

}
