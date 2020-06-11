<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // correspond to IDs in the DB table
    const DEVELOPER = 1;
    const AGENCY_USER = 2;
    const ACTIVATION_USER = 3;
    const ACT_LINE_MANAGER = 4;
    const HEAD_OF_ACT = 5;
    const VOD_USER = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * get the users belonging to this role
     *
     */
     public function users()
     {
         return $this->hasMany('App\User');
     }


}
