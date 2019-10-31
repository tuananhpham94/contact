<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'legal_name', 'email'
    ];

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }
}
