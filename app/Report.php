<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['frequency', 'recipients', 'brief_start_date', 'brief_end_date'];

    /**
     * Get the brief start date, in the format: dd/mm/yyyy.
     *
     * @param  string  $value
     * @return string
     */
    public function getBriefStartDateAttribute($value)
    {
        if($value == null){
            return null;
        }else{
            $date_time = \DateTime::createFromFormat('Y-m-d', $value);
            return $date_time->format('d/m/Y');
        }

    }

    /**
     * Get the brief end date, in the format: dd/mm/yyyy.
     *
     * @param  string  $value
     * @return string
     */
    public function getBriefEndDateAttribute($value)
    {
        if($value == null){
            return null;
        }else{
            $date_time = \DateTime::createFromFormat('Y-m-d', $value);
            return $date_time->format('d/m/Y');
        }

    }
}
