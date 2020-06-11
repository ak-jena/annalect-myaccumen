<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BriefFile extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['brief_id', 'file_name','location'];

    /**
     * Get the brief that owns this file.
     */
    public function brief()
    {
        return $this->belongsTo('App\Brief');
    }
}
