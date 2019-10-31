<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'history_id', 'company_id'
    ];
    public function userHistory()
    {
        return $this->belongsTo('App\UserHistory');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
