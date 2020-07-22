<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = array('first_name','last_name', 'password', 'email', 'company', 'phone', 'customer_type');
    public function attendees()
    {
        return $this->hasMany('App\EventAttendees','event_id','id');
    }
}
