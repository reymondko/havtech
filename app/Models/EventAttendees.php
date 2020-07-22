<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAttendees extends Model
{
    protected $table = 'event_attendees';
    protected $primaryKey = 'id';
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
    public function user()
    {
        return $this->belongsTo('App\Users','id','user_id');
    }

}
