<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    //
    /**
     * Get the statuses for the section.
     */
    public function statuses()
    {
        return $this->hasMany('App\Status');
    }
}
