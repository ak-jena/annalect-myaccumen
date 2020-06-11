<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'body','author_id', 'brief_id'];

    /**
     * Get the brief that owns this file.
     */
    public function brief()
    {
        return $this->belongsTo('App\Brief');
    }

    /**
     * Get the user that created this comment.
     */
    public function author()
    {
        return $this->belongsTo('App\User');
    }
}
