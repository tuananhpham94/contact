<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Validator;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username' ,'email', 'password', 'dob', 'tel', 'address', 'unique_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userHistory()
    {
        return $this->hasMany('App\UserHistory');
    }

    public function updateHistory($request){
        $user = $this->find($request->user()->id);

        if(!empty($request->email)) {
            $validator = Validator::make($request->all(), [
                'email' => 'unique:users,email,'.$request->user()->id
            ]);

            if($validator->fails()){
                return false;
            }
            $user->email = $request->email;
        }
        empty($request->tel) ? : $user->tel = $request->tel;
        empty($request->address) ? : $user->address = $request->address;
        $user->save();
        return true;
    }
}
