<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTravelHosts extends Model
{
    protected $table = 'event_travel_hosts';
    protected $primaryKey = 'id';
    
    public function events()
    {
        return $this->belongsTo('App\Events');
    }
}
