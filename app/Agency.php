<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','contact_user_id','logo'];

    /**
     * Get the contact user for this agency
     */
    public function contactUser()
    {
        return $this->belongsTo('App\User', 'contact_user_id');
    }

    /**
     * Get the clients belonging to this agency
     */
    public function clients()
    {
        return $this->hasMany('App\Client')->orderBy('name','asc');
    }

    /**
     * Users that belong to this agency
     * Accuen users will belong to multiple agencies
     * All other users will belong to one agency
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'agencies_users')->withTimestamps();
    }

}
